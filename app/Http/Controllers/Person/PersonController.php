<?php

namespace App\Http\Controllers\Person;

use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\Str as StrHerlper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonResquest;
use App\Models\Data\Address;
use App\Models\Data\Email;
use App\Models\Data\Image;
use App\Models\Data\Telephone;
use App\Models\Person\Person;
use App\Models\SocialNetwork;
use App\Services\VCardService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.ler')) {
            toast('Sem permissão!', 'info');
            return back();
        }

        $persons = Person::select('id', 'name', 'nickname', 'birth_date', 'cpf', 'created_at', 'dead', 'active_orcrim')
            ->orderBy('name', 'asc')->paginate(15);

        return view('person.index', compact('persons'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $request): View|Factory|RedirectResponse|Application
    {
        $request->validate(['search' => 'required|string|min:3'], ['search.required' => 'Campo de Pesquisa é obrigatório']);
        $search = Str::upper($request->search);
        $search = Str::ascii($search);
        $persons = Person::where('name', 'like', '%' . $search . '%')
            ->orWhere('nickname', 'like', '%' . $search . '%')
            ->orWhere('cpf', 'like', '%' . $search . '%')
            ->orWhere('rg', 'like', '%' . $search . '%')
            ->orWhere('tatto', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->select('id', 'name', 'nickname', 'dead', 'birth_date', 'cpf', 'active_orcrim')
            ->paginate(15);

        return view('person.index', compact('persons'))->with(['search' => $request->search]);
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.cadastrar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $address = new Address();
        $person = new Person();
        return view('person.create', compact('address', 'person'));
    }

    /**
     * @param PersonResquest $request
     * @return RedirectResponse
     */
    public function store(PersonResquest $request): RedirectResponse
    {
        try {
            StrHerlper::asciiRequest($request);
            StrHerlper::upperRequest($request);
            $personData = $request->except(['addresses', 'contacts', 'images']);
            $filledFields = array_filter($personData, fn($value) => !empty($value));
            $filledFields['user_id'] = Auth::id();
            $person = Person::create($filledFields);

            $this->attachAddresses($person, $request->input('addresses'));
            $this->attachContacts($person, $request->input('contacts'));
            $this->attachEmails($person, $request->input('emails'));
            $this->attachSocialNetworks($person, $request->input('socials'));
            $this->attachImages($person, $request->file('images'));
            $this->storeWithVCard($person, $request->file('vcard'));

            toast('Pessoa cadastrada com sucesso!', 'success');

            return redirect()->route('persons');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível cadastrar a pessoa', 'error');

            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.ler')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $person = Person::with('vcards')->find($id);

        return view('person.view.show', compact('person'));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');

            return back();
        }
        try {
            $person = Person::findOrFail($id);
            $address = new Address();
            return view('person.edit', compact('person', 'address'));
        } catch (\Exception $exception) {
            Log::error($exception);
            toast('Erro de sistema. Pessoa não localizada', 'error');

            return back();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (!Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');

            return back();
        }
        try {
            $person = Person::findOrFail($id);
            $person->fill($request->except(['addresses', 'contacts', 'images']));
            $person->dead = $request->boolean('dead', false);
            $person->active_orcrim = $request->boolean('active_orcrim', false);
            $person->warrant = $request->boolean('warrant', false);
            $person->update();
            $person->address()->forceDelete();

            if ($request->has('addresses')) {
                $this->attachAddresses($person, $request->input('addresses'));
            }
            $person->telephones()->forceDelete();

            if ($request->has('contacts')) {
                $this->attachContacts($person, $request->input('contacts'));
            }
            $person->socials()->forceDelete();

            if ($request->has('socials')) {
                $this->attachSocialNetworks($person, $request->input('socials'));
            }
            $person->emails()->forceDelete();

            if ($request->has('emails')) {
                $this->attachEmails($person, $request->input('emails'));
            }

            if ($request->hasFile('images')) {
                $this->attachImages($person, $request->file('images'));
            }

            if ($request->hasFile('vcard')) {
                $this->storeWithVCard($person, $request->file('vcard'));
            }

            toast('Pessoa atualizada com sucesso!', 'success');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema!. Não foi possível atualizar a pessoa');

            return back();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('pessoa.excluir')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        try {
            $person = Person::findOrFail($id);
            $person->delete();
            toast('Pessoa removida com sucesso!', 'success');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception);
            toast('Erro de sistema. Pessoa não localizada', 'error');

            return back();
        }
    }


    /**
     * @param $personId
     * @param $imageId
     * @return JsonResponse
     */
    public function removeImage($personId, $imageId): JsonResponse
    {
        try {
            $person = Person::findOrFail($personId);
            $person->images()->detach($imageId);
            $image = Image::find($imageId);
            if ($image->delete()) {
                return response()->json(['success' => true]);
            }
            $person->images()->attach($image->id);

            return response()->json(['success' => false, 'error' => 'Não foi possível exluir o arquivo do servidor.']);
        } catch (ModelNotFoundException $exception) {
            Log::error($exception->getMessage());

            return response()->json(['success' => false, 'error' => $exception->getMessage()]);
        }
    }

    /**
     * @param Person $person
     * @param array|null $addresses
     * @return bool
     */
    private function attachAddresses(Person $person, ?array $addresses): bool
    {
        if (!$addresses) {
            return false;
        }

        foreach ($addresses as $json) {
            $addressData = collect(json_decode($json, true))->filter()->all();
            $address = Address::create($addressData);
            $person->address()->attach($address->id);
        }

        return true;
    }

    /**
     * @param Person $person
     * @param array|null $contacts
     * @return bool
     */
    private function attachContacts(Person $person, ?array $contacts): bool
    {
        if (!$contacts) {
            return false;
        }

        foreach ($contacts as $json) {
            $contactData = collect(json_decode($json, true))->filter()->all();
            $contact = Telephone::create($contactData);
            $person->telephones()->attach($contact->id);
        }

        return true;
    }

    /**
     * @param Person $person
     * @param array|null $emails
     * @return bool
     */
    private function attachEmails(Person $person, ?array $emails): bool
    {
        if (!$emails) {
            return false;
        }

        foreach ($emails as $json) {
            $emailData = json_decode($json, true);
            $email = Email::create($emailData);
            $person->emails()->attach($email->id);
        }

        return true;
    }

    /**
     * @param Person $person
     * @param array|null $socials
     * @return bool
     */
    private function attachSocialNetworks(Person $person, ?array $socials): bool
    {
        if (!$socials) {
            return false;
        }

        foreach ($socials as $json) {
            $socialData = collect(json_decode($json, true))->filter()->all();
            $social = SocialNetwork::create($socialData);
            $person->socials()->attach($social->id);
        }

        return true;
    }

    /**
     * @param Person $person
     * @param array|null $images
     * @return bool
     */
    private function attachImages(Person $person, ?array $images): bool
    {
        if (!$images) {
            return false;
        }

        foreach ($images as $file) {
            $filename = FileHelper::filenameOriginalDate($file);
            $path = $file->storeAs('images', $filename);

            if ($path) {
                $image = Image::create(['description' => $filename, 'path' => $path]);
                $person->images()->attach($image->id);
                ImageHelper::optimizerImage($path);
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $vcardFile
     * @return bool
     */
    private function storeWithVCard(Person $person, ?array $vcardFile): bool
    {
        if (!$vcardFile) {
            return false;
        }

        foreach ($vcardFile as $file) {
            $path = $file->store('vcard', 'tmp');
            (new VCardService($person, $file, $path))->store();
        }

        return true;
    }
}
