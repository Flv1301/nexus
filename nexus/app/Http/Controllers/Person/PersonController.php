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
use App\Models\Person\PersonCompany;
use App\Models\Person\Vehicle;
use App\Models\Person\VinculoOrcrim;
use App\Models\Person\Pcpa;
use App\Models\Person\Tj;
use App\Models\Person\Arma;
use App\Models\Person\Rais;
use App\Models\Person\Bancario;
use App\Models\Person\Doc;
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

        $persons = Person::with('images')
            ->select('id', 'name', 'nickname', 'birth_date', 'cpf', 'created_at', 'dead')
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
        $searchAscii = Str::ascii($search);
        
        $persons = Person::with('images')
            ->where(function($query) use ($search, $searchAscii) {
                $query->where('name', 'ilike', '%' . $search . '%')
                      ->orWhere('name', 'ilike', '%' . $searchAscii . '%')
                      ->orWhere('nickname', 'ilike', '%' . $search . '%')
                      ->orWhere('nickname', 'ilike', '%' . $searchAscii . '%')
                      ->orWhere('cpf', 'like', '%' . $search . '%')
                      ->orWhere('rg', 'like', '%' . $search . '%')
                      ->orWhere('tatto', 'ilike', '%' . $search . '%')
                      ->orWhere('tatto', 'ilike', '%' . $searchAscii . '%');
            })
            ->orderBy('name')
            ->select('id', 'name', 'nickname', 'dead', 'birth_date', 'cpf')
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
            
            $personData = $request->except(['addresses', 'contacts', 'images', 'emails', 'socials', 'companies', 'vehicles', 'vinculo_orcrims', 'pcpas', 'tjs', 'armas', 'rais', 'bancarios', 'docs', 'visitantes', 'vcard', 'dead', 'warrant', 'evadido', 'active_orcrim']);
            $filledFields = array_filter($personData, fn($value) => !empty($value));
            $filledFields['user_id'] = Auth::id();
            
            // Tratar campos boolean especificamente para garantir que sejam salvos corretamente
            $filledFields['dead'] = $request->boolean('dead', false);
            $filledFields['warrant'] = $request->boolean('warrant', false);
            $filledFields['evadido'] = $request->boolean('evadido', false);
            $filledFields['active_orcrim'] = $request->boolean('active_orcrim', false);
            
            $person = Person::create($filledFields);

            // Attach addresses
            try {
                $this->attachAddresses($person, $request->input('addresses'));
            } catch (\Exception $e) {
                Log::error('Error attaching addresses:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar endereços: ' . $e->getMessage());
            }

            // Attach contacts
            try {
                $this->attachContacts($person, $request->input('contacts'));
            } catch (\Exception $e) {
                Log::error('Error attaching contacts:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar contatos: ' . $e->getMessage());
            }

            // Attach emails
            try {
                $this->attachEmails($person, $request->input('emails'));
            } catch (\Exception $e) {
                Log::error('Error attaching emails:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar emails: ' . $e->getMessage());
            }

            // Attach social networks
            try {
                $this->attachSocialNetworks($person, $request->input('socials'));
            } catch (\Exception $e) {
                Log::error('Error attaching socials:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar redes sociais: ' . $e->getMessage());
            }

            // Attach images
            try {
                $this->attachImages($person, $request->file('images'));
            } catch (\Exception $e) {
                Log::error('Error attaching images:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar imagens: ' . $e->getMessage());
            }

            // Store VCard
            try {
                $this->storeWithVCard($person, $request->file('vcard'));
            } catch (\Exception $e) {
                Log::error('Error storing vcard:', ['error' => $e->getMessage()]);
                throw new \Exception('Erro ao processar VCard: ' . $e->getMessage());
            }

            // Lógica para salvar as empresas
            if ($request->has('companies')) {
                try {
                    $this->attachCompanies($person, $request->input('companies'));
                } catch (\Exception $e) {
                    Log::error('Error attaching companies:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar empresas: ' . $e->getMessage());
                }
            }

            // Lógica para salvar veículos
            if ($request->has('vehicles')) {
                try {
                    Log::info('Checking for vehicles data in request', [
                        'has_vehicles' => $request->has('vehicles'),
                        'vehicles_input' => $request->input('vehicles')
                    ]);
                    $this->attachVehicles($person, $request->input('vehicles'));
                } catch (\Exception $e) {
                    Log::error('Error attaching vehicles:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar veículos: ' . $e->getMessage());
                }
            }

            // Lógica para salvar vínculos de orcrim
            if ($request->has('vinculo_orcrims')) {
                try {
                    $this->attachVinculoOrcrims($person, $request->input('vinculo_orcrims'));
                } catch (\Exception $e) {
                    Log::error('Error attaching vinculo orcrims:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar vínculos de orcrim: ' . $e->getMessage());
                }
            }

            // Lógica para salvar PCPAs
            if ($request->has('pcpas')) {
                try {
                    $this->attachPcpas($person, $request->input('pcpas'));
                } catch (\Exception $e) {
                    Log::error('Error attaching pcpas:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar PCPAs: ' . $e->getMessage());
                }
            }

            // Lógica para salvar TJs
            if ($request->has('tjs')) {
                try {
                    $this->attachTjs($person, $request->input('tjs'));
                } catch (\Exception $e) {
                    Log::error('Error attaching tjs:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar TJs: ' . $e->getMessage());
                }
            }

            // Lógica para salvar Armas
            if ($request->has('armas')) {
                try {
                    $this->attachArmas($person, $request->input('armas'));
                } catch (\Exception $e) {
                    Log::error('Error attaching armas:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar Armas: ' . $e->getMessage());
                }
            }

            // Lógica para salvar RAIS
            if ($request->has('rais')) {
                try {
                    $this->attachRais($person, $request->input('rais'));
                } catch (\Exception $e) {
                    Log::error('Error attaching rais:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar RAIS: ' . $e->getMessage());
                }
            }

            // Lógica para salvar BNMP
            if ($request->has('bnmps')) {
                try {
                    $this->attachBnmps($person, $request->input('bnmps'));
                } catch (\Exception $e) {
                    Log::error('Error attaching bnmps:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar BNMP: ' . $e->getMessage());
                }
            }

            // Lógica para salvar Bancários
            if ($request->has('bancarios')) {
                try {
                    $this->attachBancarios($person, $request->input('bancarios'));
                } catch (\Exception $e) {
                    Log::error('Error attaching bancarios:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar Bancários: ' . $e->getMessage());
                }
            }

            // Lógica para salvar Docs
            if ($request->has('new_docs')) {
                $this->attachDocs($person, $request->input('new_docs'), $request->file('doc_uploads'));
            }

            // Lógica para salvar Visitantes
            if ($request->has('visitantes')) {
                try {
                    $this->attachVisitantes($person, $request->input('visitantes'));
                } catch (\Exception $e) {
                    Log::error('Error attaching visitantes:', ['error' => $e->getMessage()]);
                    throw new \Exception('Erro ao processar Visitantes: ' . $e->getMessage());
                }
            }

            toast('Pessoa cadastrada com sucesso!', 'success');

            return redirect()->route('persons');
        } catch (\Exception $exception) {
            Log::error('Error in store method:', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            // Se a pessoa foi criada mas houve erro nos anexos, tenta deletar
            if (isset($person) && $person->id) {
                try {
                    $person->delete();
                    Log::info('Person deleted due to error in attachments');
                } catch (\Exception $deleteException) {
                    Log::error('Failed to delete person after error:', ['error' => $deleteException->getMessage()]);
                }
            }
            
            toast($exception->getMessage(), 'error');

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

        $person = Person::with(['vcards', 'companies', 'vehicles', 'vinculoOrcrims', 'pcpas', 'tjs', 'armas', 'rais', 'bancarios', 'docs', 'visitantes'])->find($id);

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
            $person = Person::with(['address', 'telephones', 'emails', 'socials', 'companies', 'vehicles', 'vinculoOrcrims', 'pcpas', 'tjs', 'armas', 'rais', 'bancarios', 'docs', 'visitantes'])->findOrFail($id);
            $address = new Address();
            return view('person.edit', compact('person', 'address'));
        } catch (\Exception $exception) {
            Log::error($exception);
            toast('Erro de sistema. Pessoa não localizada', 'error');

            return back();
        }
    }

    /**
     * @param PersonResquest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(PersonResquest $request, $id): RedirectResponse
    {
        if (!Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');

            return back();
        }
        try {
            // Debug log para verificar dados recebidos
            Log::info('Update request data received:', [
                'has_removed_docs' => $request->has('removed_docs'),
                'removed_docs' => $request->input('removed_docs'),
                'has_new_docs' => $request->has('new_docs'),
                'new_docs' => $request->input('new_docs'),
                'all_request_keys' => array_keys($request->all())
            ]);

            $person = Person::findOrFail($id);
            
            // Processar campos não-boolean excluindo os arrays e campos boolean
            $personData = $request->except(['addresses', 'contacts', 'images', 'emails', 'socials', 'companies', 'vehicles', 'vinculo_orcrims', 'pcpas', 'tjs', 'armas', 'rais', 'bancarios', 'docs', 'visitantes', 'vcard', 'dead', 'warrant', 'evadido', 'active_orcrim']);
            $person->fill($personData);
            
            // Tratar campos boolean especificamente
            $person->dead = $request->boolean('dead', false);
            $person->warrant = $request->boolean('warrant', false);
            $person->evadido = $request->boolean('evadido', false);
            $person->active_orcrim = $request->boolean('active_orcrim', false);
            
            $person->update();
            
            // Processar endereços de forma mais segura
            Log::info('Processing addresses in update:', [
                'has_addresses' => $request->has('addresses'),
                'addresses_count' => $request->has('addresses') ? count($request->input('addresses')) : 0,
                'addresses_data' => $request->input('addresses')
            ]);
            
            if ($request->has('addresses')) {
                // Primeiro deletar endereços existentes
                $person->address()->forceDelete();
                // Depois adicionar os novos (incluindo os que foram reenviados)
                $this->attachAddresses($person, $request->input('addresses'));
            } else {
                // Se não há endereços na requisição, manter os existentes
                Log::info('No addresses in request, keeping existing addresses');
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

            // Lógica para salvar as empresas
            $person->companies()->forceDelete();
            if ($request->has('companies')) {
                $this->attachCompanies($person, $request->input('companies'));
            }

            // Lógica para salvar veículos
            Log::info('Checking for vehicles data in update request', [
                'has_vehicles' => $request->has('vehicles'),
                'vehicles_input' => $request->input('vehicles'),
                'all_request_data' => $request->all()
            ]);
            
            $person->vehicles()->delete();
            if ($request->has('vehicles')) {
                $this->attachVehicles($person, $request->input('vehicles'));
            }

            // Lógica para salvar vínculos de orcrim
            $person->vinculoOrcrims()->delete();
            if ($request->has('vinculo_orcrims')) {
                $this->attachVinculoOrcrims($person, $request->input('vinculo_orcrims'));
            }

            // Lógica para salvar PCPAs
            $person->pcpas()->delete();
            if ($request->has('pcpas')) {
                $this->attachPcpas($person, $request->input('pcpas'));
            }

            // Lógica para salvar TJs
            $person->tjs()->delete();
            if ($request->has('tjs')) {
                $this->attachTjs($person, $request->input('tjs'));
            }

            // Lógica para salvar Armas
            $person->armas()->delete();
            if ($request->has('armas')) {
                $this->attachArmas($person, $request->input('armas'));
            }

            // Lógica para salvar RAIS
            $person->rais()->delete();
            if ($request->has('rais')) {
                $this->attachRais($person, $request->input('rais'));
            }

            // Lógica para salvar BNMP
            $person->bnmps()->delete();
            if ($request->has('bnmps')) {
                $this->attachBnmps($person, $request->input('bnmps'));
            }

            // Lógica para salvar Bancários
            $person->bancarios()->delete();
            if ($request->has('bancarios')) {
                $this->attachBancarios($person, $request->input('bancarios'));
            }

            // Lógica para salvar Docs
            // Primeiro, processar documentos removidos
            if ($request->has('removed_docs')) {
                $this->removeSpecificDocs($person, $request->input('removed_docs'));
            }
            
            // Depois, adicionar novos documentos
            if ($request->has('new_docs')) {
                $this->attachDocs($person, $request->input('new_docs'), $request->file('doc_uploads'));
            }

            // Lógica para salvar Visitantes
            $person->visitantes()->delete();
            if ($request->has('visitantes')) {
                $this->attachVisitantes($person, $request->input('visitantes'));
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
        $person = Person::findOrFail($personId);
        $image = Image::findOrFail($imageId);

        $person->images()->detach($imageId);

        if ($person->images()->count() === 0) {
            $person->images()->attach($image->id);
        }

        return response()->json(['success' => true, 'message' => 'Imagem removida com sucesso!']);
    }

    /**
     * Serve document with proper authentication and authorization
     * 
     * @param int $personId
     * @param int $docId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function serveDocument($personId, $docId)
    {
        try {
            // Verificar se a pessoa existe
            $person = Person::findOrFail($personId);
            
            // Verificar se o documento existe e pertence à pessoa
            $doc = $person->docs()->findOrFail($docId);
            
            // Verificar se o arquivo existe fisicamente
            $filePath = public_path($doc->upload);
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Arquivo não encontrado'], 404);
            }
            
            // Log de acesso para auditoria
            \Log::info('Document accessed', [
                'user_id' => auth()->id(),
                'person_id' => $personId,
                'doc_id' => $docId,
                'file_path' => $doc->upload,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Detectar tipo de conteúdo
            $mimeType = mime_content_type($filePath);
            
            // Servir o arquivo com headers apropriados para PDF.js
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($doc->upload) . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
                'Access-Control-Allow-Origin' => request()->getSchemeAndHttpHost(),
                'Access-Control-Allow-Methods' => 'GET',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error serving document', [
                'user_id' => auth()->id(),
                'person_id' => $personId,
                'doc_id' => $docId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Erro ao acessar documento'], 500);
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
            Log::info('No addresses data received');
            return false;
        }

        Log::info('Addresses data received:', ['addresses' => $addresses]);

        foreach ($addresses as $key => $json) {
            try {
                Log::info('Processing address at index ' . $key, [
                    'data_type' => gettype($json),
                    'data' => $json
                ]);

                $addressData = collect(json_decode($json, true))->filter()->all();
                Log::info('Address data processed:', ['address' => $addressData]);
                
                $address = Address::create($addressData);
                $person->address()->attach($address->id);
                
                Log::info('Address created and attached successfully:', ['address_id' => $address->id]);
            } catch (\Exception $e) {
                Log::error('Error creating address:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'address_data' => $json ?? null
                ]);
                throw $e;
            }
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

    /**
     * @param Person $person
     * @param array|null $companies
     * @return bool
     */
    private function attachCompanies(Person $person, ?array $companies): bool
    {
        if (!$companies) {
            Log::info('No companies data received');
            return false;
        }

        Log::info('Companies data received:', ['companies' => $companies]);
        
        foreach ($companies as $key => $companyData) {
            try {
                Log::info('Processing company at index ' . $key, [
                    'data_type' => gettype($companyData),
                    'data' => $companyData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($companyData)) {
                    $companyData = json_decode($companyData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $companyData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($companyData)) {
                    Log::error('Invalid company data format:', ['data' => $companyData]);
                    continue;
                }

                // Filtra campos vazios
                $companyData = collect($companyData)->filter()->all();
                
                Log::info('Processing company data:', ['company' => $companyData]);
                
                // Verifica se tem pelo menos o nome da empresa
                if (empty($companyData['company_name'])) {
                    Log::warning('Company skipped - missing company name');
                    continue;
                }

                // Limpa o valor do capital social
                $socialCapital = $this->cleanMoneyValue($companyData['social_capital'] ?? null);

                $company = PersonCompany::create([
                    'person_id' => $person->id,
                    'company_name' => $companyData['company_name'] ?? null,
                    'fantasy_name' => $companyData['fantasy_name'] ?? null,
                    'cnpj' => $companyData['cnpj'] ?? null,
                    'phone' => $companyData['phone'] ?? null,
                    'social_capital' => $socialCapital,
                    'status' => $companyData['status'] ?? null,
                    'cep' => $companyData['cep'] ?? null,
                    'address' => $companyData['address'] ?? null,
                    'number' => $companyData['number'] ?? null,
                    'district' => $companyData['district'] ?? null,
                    'city' => $companyData['city'] ?? null,
                    'uf' => $companyData['uf'] ?? null,
                    'cnae' => $companyData['cnae'] ?? null,
                    'accountant' => $companyData['accountant'] ?? null,
                ]);

                Log::info('Company created successfully:', ['company_id' => $company->id]);
            } catch (\Exception $e) {
                Log::error('Error creating company:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'company_data' => $companyData ?? null
                ]);
                throw $e;
            }
        }

        return true;
    }

    /**
     * Limpa valores monetários removendo símbolos e formatação
     * @param string|null $value
     * @return float|null
     */
    private function cleanMoneyValue(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Remove símbolos de moeda e espaços
        $cleaned = preg_replace('/[R$\s]/', '', $value);
        
        // Se tem vírgula, assume formato brasileiro (pontos = milhares, vírgula = decimal)
        if (strpos($cleaned, ',') !== false) {
            // Remove pontos (separadores de milhares) e substitui vírgula por ponto decimal
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        }
        // Se não tem vírgula mas tem ponto, assume formato americano ou valor sem centavos
        // Não remove o ponto neste caso
        
        // Verifica se é um valor numérico válido
        if (is_numeric($cleaned)) {
            return (float) $cleaned;
        }
        
        return null;
    }

    /**
     * @param Person $person
     * @param array|null $vehicles
     * @return bool
     */
    private function attachVehicles(Person $person, ?array $vehicles): bool
    {
        if (!$vehicles) {
            Log::info('No vehicles data received');
            return false;
        }

        Log::info('Vehicles data received:', ['vehicles' => $vehicles]);
        Log::info('Vehicles data type:', ['type' => gettype($vehicles)]);
        
        foreach ($vehicles as $key => $vehicleData) {
            try {
                Log::info('Processing vehicle at index ' . $key, [
                    'data_type' => gettype($vehicleData),
                    'data' => $vehicleData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($vehicleData)) {
                    $vehicleData = json_decode($vehicleData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $vehicleData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($vehicleData)) {
                    Log::error('Invalid vehicle data format:', ['data' => $vehicleData]);
                    continue;
                }

                // Verifica se tem brand
                if (empty($vehicleData['brand'])) {
                    Log::warning('Vehicle skipped - missing brand');
                    continue;
                }

                Log::info('Processing vehicle data:', ['vehicle' => $vehicleData]);
                
                $vehicle = $person->vehicles()->create([
                    'brand' => $vehicleData['brand'],
                    'model' => $vehicleData['model'] ?? null,
                    'year' => $vehicleData['year'] ?? null,
                    'color' => $vehicleData['color'] ?? null,
                    'plate' => $vehicleData['plate'] ?? null,
                    'jurisdiction' => $vehicleData['jurisdiction'] ?? null,
                    'status' => $vehicleData['status'] ?? null,
                    'renavam' => $vehicleData['renavam'] ?? null,
                    'chassi' => $vehicleData['chassi'] ?? null,
                ]);

                Log::info('Vehicle created successfully:', ['vehicle_id' => $vehicle->id, 'vehicle_data' => $vehicle->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating vehicle:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'vehicle_data' => $vehicleData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $vinculoOrcrims
     * @return bool
     */
    private function attachVinculoOrcrims(Person $person, ?array $vinculoOrcrims): bool
    {
        if (!$vinculoOrcrims) {
            Log::info('No vinculo orcrims data received');
            return false;
        }

        Log::info('Vinculo orcrims data received:', ['vinculo_orcrims' => $vinculoOrcrims]);
        
        foreach ($vinculoOrcrims as $key => $vinculoData) {
            try {
                Log::info('Processing vinculo orcrim at index ' . $key, [
                    'data_type' => gettype($vinculoData),
                    'data' => $vinculoData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($vinculoData)) {
                    $vinculoData = json_decode($vinculoData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $vinculoData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($vinculoData)) {
                    Log::error('Invalid vinculo orcrim data format:', ['data' => $vinculoData]);
                    continue;
                }

                // Verifica se tem name
                if (empty($vinculoData['name'])) {
                    Log::warning('Vinculo orcrim skipped - missing name');
                    continue;
                }

                Log::info('Processing vinculo orcrim data:', ['vinculo' => $vinculoData]);
                
                $vinculo = $person->vinculoOrcrims()->create([
                    'name' => $vinculoData['name'],
                    'cpf' => $vinculoData['cpf'] ?? null,
                    'alcunha' => $vinculoData['alcunha'] ?? null,
                    'tipo_vinculo' => $vinculoData['tipo_vinculo'] ?? null,
                    'orcrim' => $vinculoData['orcrim'] ?? null,
                    'cargo' => $vinculoData['cargo'] ?? null,
                    'area_atuacao' => $vinculoData['area_atuacao'] ?? null,
                    'matricula' => $vinculoData['matricula'] ?? null,
                ]);

                Log::info('Vinculo orcrim created successfully:', ['vinculo_id' => $vinculo->id, 'vinculo_data' => $vinculo->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating vinculo orcrim:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'vinculo_data' => $vinculoData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $pcpas
     * @return bool
     */
    private function attachPcpas(Person $person, ?array $pcpas): bool
    {
        if (!$pcpas) {
            Log::info('No pcpas data received');
            return false;
        }

        Log::info('PCPAs data received:', ['pcpas' => $pcpas]);
        
        foreach ($pcpas as $key => $pcpaData) {
            try {
                Log::info('Processing PCPA at index ' . $key, [
                    'data_type' => gettype($pcpaData),
                    'data' => $pcpaData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($pcpaData)) {
                    $pcpaData = json_decode($pcpaData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $pcpaData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($pcpaData)) {
                    Log::error('Invalid PCPA data format:', ['data' => $pcpaData]);
                    continue;
                }

                // Verifica se tem BO
                if (empty($pcpaData['bo'])) {
                    Log::warning('PCPA skipped - missing BO');
                    continue;
                }

                Log::info('Processing PCPA data:', ['pcpa' => $pcpaData]);
                
                $pcpa = $person->pcpas()->create([
                    'bo' => $pcpaData['bo'],
                    'natureza' => !empty($pcpaData['natureza']) ? $pcpaData['natureza'] : null,
                    'data' => !empty($pcpaData['data']) ? $pcpaData['data'] : null,
                    'uf' => !empty($pcpaData['uf']) ? $pcpaData['uf'] : null,
                    'cidade' => !empty($pcpaData['cidade']) ? $pcpaData['cidade'] : null,
                ]);

                Log::info('PCPA created successfully:', ['pcpa_id' => $pcpa->id, 'pcpa_data' => $pcpa->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating PCPA:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'pcpa_data' => $pcpaData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $tjs
     * @return bool
     */
    private function attachTjs(Person $person, ?array $tjs): bool
    {
        if (!$tjs) {
            Log::info('No tjs data received');
            return false;
        }

        Log::info('TJs data received:', ['tjs' => $tjs]);
        
        foreach ($tjs as $key => $tjData) {
            try {
                Log::info('Processing TJ at index ' . $key, [
                    'data_type' => gettype($tjData),
                    'data' => $tjData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($tjData)) {
                    $tjData = json_decode($tjData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $tjData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($tjData)) {
                    Log::error('Invalid TJ data format:', ['data' => $tjData]);
                    continue;
                }

                // Verifica se tem processo
                if (empty($tjData['processo'])) {
                    Log::warning('TJ skipped - missing processo');
                    continue;
                }

                Log::info('Processing TJ data:', ['tj' => $tjData]);
                
                $tj = $person->tjs()->create([
                    'situacao' => !empty($tjData['situacao']) ? $tjData['situacao'] : null,
                    'data_denuncia' => !empty($tjData['data_denuncia']) ? $tjData['data_denuncia'] : null,
                    'data_condenacao' => !empty($tjData['data_condenacao']) ? $tjData['data_condenacao'] : null,
                    'processo' => $tjData['processo'],
                    'natureza' => !empty($tjData['natureza']) ? $tjData['natureza'] : null,
                    'classe' => !empty($tjData['classe']) ? $tjData['classe'] : null,
                    'autor' => !empty($tjData['autor']) ? $tjData['autor'] : null,
                    'data' => !empty($tjData['data']) ? $tjData['data'] : null,
                    'uf' => !empty($tjData['uf']) ? $tjData['uf'] : null,
                    'comarca' => !empty($tjData['comarca']) ? $tjData['comarca'] : null,
                    // Novos campos adicionados
                    'jurisdicao' => !empty($tjData['jurisdicao']) ? $tjData['jurisdicao'] : null,
                    'processo_prevento' => !empty($tjData['processo_prevento']) ? $tjData['processo_prevento'] : null,
                    'situacao_processo' => !empty($tjData['situacao_processo']) ? $tjData['situacao_processo'] : null,
                    'distribuicao' => !empty($tjData['distribuicao']) ? $tjData['distribuicao'] : null,
                    'orgao_julgador' => !empty($tjData['orgao_julgador']) ? $tjData['orgao_julgador'] : null,
                    'orgao_julgador_colegiado' => !empty($tjData['orgao_julgador_colegiado']) ? $tjData['orgao_julgador_colegiado'] : null,
                    'competencia' => !empty($tjData['competencia']) ? $tjData['competencia'] : null,
                    'numero_inquerito_policial' => !empty($tjData['numero_inquerito_policial']) ? $tjData['numero_inquerito_policial'] : null,
                    'valor_causa' => !empty($tjData['valor_causa']) ? (float) $tjData['valor_causa'] : null,
                    'advogado' => !empty($tjData['advogado']) ? $tjData['advogado'] : null,
                    'prioridade' => $this->convertToBoolean($tjData['prioridade'] ?? null),
                    'gratuidade' => $this->convertToBoolean($tjData['gratuidade'] ?? null),
                ]);

                Log::info('TJ created successfully:', ['tj_id' => $tj->id, 'tj_data' => $tj->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating TJ:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'tj_data' => $tjData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * Convert value to boolean with proper handling of string values
     * @param mixed $value
     * @return bool|null
     */
    private function convertToBoolean($value)
    {
        // Se valor não foi fornecido ou é string vazia, retorna null
        if ($value === null || $value === '') {
            return null;
        }
        
        // Se é string, converte baseado no valor
        if (is_string($value)) {
            $value = strtolower(trim($value));
            if ($value === '1' || $value === 'true' || $value === 'sim') {
                return true;
            }
            if ($value === '0' || $value === 'false' || $value === 'não' || $value === 'nao') {
                return false;
            }
        }
        
        // Para outros tipos, usa conversão padrão do PHP
        return (bool) $value;
    }

    /**
     * @param Person $person
     * @param array|null $armas
     * @return bool
     */
    private function attachArmas(Person $person, ?array $armas): bool
    {
        if (!$armas) {
            Log::info('No armas data received');
            return false;
        }

        Log::info('Armas data received:', ['armas' => $armas]);
        
        foreach ($armas as $key => $armaData) {
            try {
                Log::info('Processing Arma at index ' . $key, [
                    'data_type' => gettype($armaData),
                    'data' => $armaData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($armaData)) {
                    $armaData = json_decode($armaData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $armaData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($armaData)) {
                    Log::error('Invalid Arma data format:', ['data' => $armaData]);
                    continue;
                }

                // Verifica se tem pelo menos um campo preenchido
                if (empty($armaData['cac']) && empty($armaData['marca']) && empty($armaData['modelo']) && empty($armaData['calibre']) && empty($armaData['sinarm'])) {
                    Log::warning('Arma skipped - all fields empty');
                    continue;
                }

                Log::info('Processing Arma data:', ['arma' => $armaData]);
                
                $arma = $person->armas()->create([
                    'cac' => $armaData['cac'] ?? null,
                    'marca' => $armaData['marca'] ?? null,
                    'modelo' => $armaData['modelo'] ?? null,
                    'calibre' => $armaData['calibre'] ?? null,
                    'sinarm' => $armaData['sinarm'] ?? null,
                ]);

                Log::info('Arma created successfully:', ['arma_id' => $arma->id, 'arma_data' => $arma->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating Arma:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'arma_data' => $armaData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $rais
     * @return bool
     */
    private function attachRais(Person $person, ?array $rais): bool
    {
        if (!$rais) {
            Log::info('No rais data received');
            return false;
        }

        Log::info('RAIS data received:', ['rais' => $rais]);
        
        foreach ($rais as $key => $raisData) {
            try {
                Log::info('Processing RAIS at index ' . $key, [
                    'data_type' => gettype($raisData),
                    'data' => $raisData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($raisData)) {
                    $raisData = json_decode($raisData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $raisData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($raisData)) {
                    Log::error('Invalid RAIS data format:', ['data' => $raisData]);
                    continue;
                }

                // Verifica se tem empresa/orgão
                if (empty($raisData['empresa_orgao'])) {
                    Log::warning('RAIS skipped - missing empresa_orgao');
                    continue;
                }

                Log::info('Processing RAIS data:', ['rais' => $raisData]);
                
                $raisRecord = $person->rais()->create([
                    'empresa_orgao' => $raisData['empresa_orgao'],
                    'cnpj' => $raisData['cnpj'] ?? null,
                    'tipo_vinculo' => $raisData['tipo_vinculo'] ?? null,
                    'admissao' => $raisData['admissao'] ?? null,
                    'situacao' => $raisData['situacao'] ?? null,
                ]);

                Log::info('RAIS created successfully:', ['rais_id' => $raisRecord->id, 'rais_data' => $raisRecord->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating RAIS:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'rais_data' => $raisData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $bnmps
     * @return bool
     */
    private function attachBnmps(Person $person, ?array $bnmps): bool
    {
        if (!$bnmps) {
            Log::info('No bnmps data received');
            return false;
        }

        Log::info('BNMP data received:', ['bnmps' => $bnmps]);
        
        foreach ($bnmps as $key => $bnmpData) {
            try {
                Log::info('Processing BNMP at index ' . $key, [
                    'data_type' => gettype($bnmpData),
                    'data' => $bnmpData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($bnmpData)) {
                    $bnmpData = json_decode($bnmpData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $bnmpData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($bnmpData)) {
                    Log::error('Invalid BNMP data format:', ['data' => $bnmpData]);
                    continue;
                }

                // Verifica se tem número do mandado
                if (empty($bnmpData['numero_mandado'])) {
                    Log::warning('BNMP skipped - missing numero_mandado');
                    continue;
                }

                Log::info('Processing BNMP data:', ['bnmp' => $bnmpData]);
                
                $bnmpRecord = $person->bnmps()->create([
                    'numero_mandado' => $bnmpData['numero_mandado'],
                    'orgao_expedidor' => $bnmpData['orgao_expedidor'] ?? null,
                ]);

                Log::info('BNMP created successfully:', ['bnmp_id' => $bnmpRecord->id, 'bnmp_data' => $bnmpRecord->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating BNMP:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'bnmp_data' => $bnmpData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $bancarios
     * @return bool
     */
    private function attachBancarios(Person $person, ?array $bancarios): bool
    {
        if (!$bancarios) {
            Log::info('No bancarios data received');
            return false;
        }

        Log::info('Bancarios data received:', ['bancarios' => $bancarios]);
        
        foreach ($bancarios as $key => $bancarioData) {
            try {
                Log::info('Processing Bancario at index ' . $key, [
                    'data_type' => gettype($bancarioData),
                    'data' => $bancarioData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($bancarioData)) {
                    $bancarioData = json_decode($bancarioData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $bancarioData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($bancarioData)) {
                    Log::error('Invalid Bancario data format:', ['data' => $bancarioData]);
                    continue;
                }

                // Verifica se tem banco
                if (empty($bancarioData['banco'])) {
                    Log::warning('Bancario skipped - missing banco');
                    continue;
                }

                Log::info('Processing Bancario data:', ['bancario' => $bancarioData]);
                
                $bancario = $person->bancarios()->create([
                    'banco' => $bancarioData['banco'],
                    'conta' => $bancarioData['conta'] ?? null,
                    'agencia' => $bancarioData['agencia'] ?? null,
                    'data_criacao' => $bancarioData['data_criacao'] ?? null,
                    'data_exclusao' => $bancarioData['data_exclusao'] ?? null,
                ]);

                Log::info('Bancario created successfully:', ['bancario_id' => $bancario->id, 'bancario_data' => $bancario->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating Bancario:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'bancario_data' => $bancarioData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $docs
     * @param array|null $uploads
     * @return bool
     */
    private function attachDocs(Person $person, ?array $docs, ?array $uploads): bool
    {
        if (!$docs) {
            Log::info('No docs data received');
            return false;
        }

        Log::info('Docs data received:', ['docs' => $docs]);
        
        foreach ($docs as $key => $docData) {
            try {
                Log::info('Processing Doc at index ' . $key, [
                    'data_type' => gettype($docData),
                    'data' => $docData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($docData)) {
                    $docData = json_decode($docData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $docData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($docData)) {
                    Log::error('Invalid Doc data format:', ['data' => $docData]);
                    continue;
                }

                // Verifica se tem nome do documento
                if (empty($docData['nome_doc'])) {
                    Log::warning('Doc skipped - missing nome_doc');
                    continue;
                }

                Log::info('Processing Doc data:', ['doc' => $docData]);
                
                $uploadPath = null;
                
                // Verifica se há arquivo de upload correspondente
                if ($uploads && isset($uploads[$key])) {
                    $file = $uploads[$key];
                    if ($file && $file->isValid()) {
                        // Verifica se o diretório existe, senão cria
                        $uploadDir = public_path('uploads/docs');
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        // Gera nome único para o arquivo
                        $filename = time() . '_' . $file->getClientOriginalName();
                        // Salva diretamente no public/uploads/docs
                        $file->move($uploadDir, $filename);
                        $uploadPath = 'uploads/docs/' . $filename;
                        Log::info('File uploaded successfully:', ['path' => $uploadPath]);
                    }
                }
                
                $doc = $person->docs()->create([
                    'nome_doc' => $docData['nome_doc'],
                    'fonte' => $docData['fonte'] ?? null,
                    'data' => $docData['data'] ?? null,
                    'upload' => $uploadPath,
                ]);

                Log::info('Doc created successfully:', ['doc_id' => $doc->id, 'doc_data' => $doc->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating Doc:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'doc_data' => $docData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }

    /**
     * @param Person $person
     * @param string|array $removedDocs
     * @return bool
     */
    private function removeSpecificDocs(Person $person, $removedDocs): bool
    {
        Log::info('removeSpecificDocs called:', [
            'person_id' => $person->id,
            'removedDocs_type' => gettype($removedDocs),
            'removedDocs_value' => $removedDocs
        ]);

        if (!$removedDocs) {
            Log::info('No removed docs data received');
            return false;
        }

        // Se for string JSON, decodifica
        if (is_string($removedDocs)) {
            $removedDocs = json_decode($removedDocs, true);
            Log::info('Decoded removedDocs:', ['decoded' => $removedDocs]);
        }

        if (!is_array($removedDocs)) {
            Log::error('Invalid removed docs format:', ['data' => $removedDocs]);
            return false;
        }

        Log::info('Processing removed docs:', ['count' => count($removedDocs), 'docs' => $removedDocs]);
        
        foreach ($removedDocs as $docId) {
            try {
                Log::info('Processing doc removal, ID: ' . $docId);

                // Busca o documento que pertence à pessoa específica
                $doc = $person->docs()->find($docId);
                if ($doc) {
                    Log::info('Document found, preparing to delete:', ['doc' => $doc->toArray()]);
                    
                    // Remove o arquivo físico se existir
                    if ($doc->upload && file_exists(public_path($doc->upload))) {
                        unlink(public_path($doc->upload));
                        Log::info('Physical file removed:', ['path' => $doc->upload]);
                    }
                    
                    // Remove o documento do banco
                    $doc->delete();
                    Log::info('Doc removed successfully:', ['doc_id' => $docId]);
                } else {
                    Log::warning('Doc not found or does not belong to this person:', ['doc_id' => $docId, 'person_id' => $person->id]);
                }
            } catch (\Exception $e) {
                Log::error('Error removing doc:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'doc_id' => $docId
                ]);
                throw $e;
            }
        }
        
        Log::info('removeSpecificDocs completed successfully');
        return true;
    }

    /**
     * @param Person $person
     * @param array|null $visitantes
     * @return bool
     */
    private function attachVisitantes(Person $person, ?array $visitantes): bool
    {
        if (!$visitantes) {
            Log::info('No visitantes data received');
            return false;
        }

        Log::info('Visitantes data received:', ['visitantes' => $visitantes]);
        
        foreach ($visitantes as $key => $visitanteData) {
            try {
                Log::info('Processing Visitante at index ' . $key, [
                    'data_type' => gettype($visitanteData),
                    'data' => $visitanteData
                ]);

                // Se for string JSON, decodifica; se já for array, usa direto
                if (is_string($visitanteData)) {
                    $visitanteData = json_decode($visitanteData, true);
                    Log::info('Decoded JSON data:', ['decoded' => $visitanteData]);
                }
                
                // Verifica se é um array válido
                if (!is_array($visitanteData)) {
                    Log::error('Invalid Visitante data format:', ['data' => $visitanteData]);
                    continue;
                }

                // Verifica se tem nome e tipo de vínculo
                if (empty($visitanteData['nome']) || empty($visitanteData['tipo_vinculo'])) {
                    Log::warning('Visitante skipped - missing required fields');
                    continue;
                }

                Log::info('Processing Visitante data:', ['visitante' => $visitanteData]);
                
                $visitante = $person->visitantes()->create([
                    'nome' => $visitanteData['nome'],
                    'cpf' => $visitanteData['cpf'] ?? null,
                    'tipo_vinculo' => $visitanteData['tipo_vinculo'],
                ]);

                Log::info('Visitante created successfully:', ['visitante_id' => $visitante->id, 'visitante_data' => $visitante->toArray()]);
            } catch (\Exception $e) {
                Log::error('Error creating Visitante:', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'visitante_data' => $visitanteData ?? null
                ]);
                throw $e;
            }
        }
        return true;
    }
}
