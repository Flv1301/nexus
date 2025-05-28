<?php

namespace App\Http\Controllers\Search;

use App\APIs\CortexApi;
use App\Helpers\Str as StrHerlper;
use App\Http\Controllers\Controller;
use App\Models\Person\Person;
use App\Services\PersonSearchService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PersonSearchController extends Controller
{
    /**
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Factory|View|Application
    {
        $bases = $this->getCachedBases();
        $request = $this->getRequestSession();
        return view('search.person.index', compact('bases', 'request'));
    }

    /**
     * @return array|mixed
     */
    private function getCachedBases(): mixed
    {
        $id = Auth::id();

        if (Cache::has('person_search_' . $id)) {
            return Cache::get('person_search_' . $id);
        }
        return [];
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $request): View|Factory|RedirectResponse|Application
    {
        try {
            if (!$request->filled('options')) {
                toast('Por favor, selecione alguma base de dados para realizar a pesquisa.', 'info');

                return back()->withInput();
            }

            if (!$this->inputFilterRequestEmpty($request->except('options', '_token'))) {
                toast('Por favor, preencha pelo menos um campo para realizar a pesquisa.', 'info');
                return back()->withInput();
            }

            #StrHerlper::asciiRequest($request, $request->except('_token', 'options'));
            StrHerlper::upperRequest($request, $request->except('_token', 'options'));

            $id = Auth::id();
            $inputHash = $this->generateInputHash($request);

            if (Session::has('hashInput') && Session::get('hashInput') !== $inputHash) {
                Cache::forget('person_search_' . $id);
            }

            $bases = cache()->remember('person_search_' . $id, now()->addMinute(5), function () use ($request) {
                $bases = [];
                foreach ($request->options as $option) {
                    $bases[$option] = $this->$option($request);
                }

                return $bases;
            });
            session()->put('hashInput', $inputHash);
            session()->put('request_search', $request->except('_token'));

            return view('search.person.index', compact('bases', 'request'));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível realizar a busca', 'error');

            return back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function bnmp(Request $request): Collection
    {
        $excludedFields = $request->except('father', 'rg', 'lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }
        $bnmpCortex = new CortexApi();
        $data = collect([]);

        if ($request->filled('name', 'mother')) {
            $response = $bnmpCortex->personSearchBnmpNameAndMother($request->name, $request->mother);
            if ($response && !array_key_exists('error', $response)) {
                $data = $data->concat($this->getPersonsCortexBnmp($response));
            }
        }

        if ($request->filled('name', 'birth_date')) {
            $response = $bnmpCortex->personSearchBnmpNameAndBirthDate($request->name, $request->birth_date);
            if ($response && !array_key_exists('error', $response)) {
                $data = $data->concat($this->getPersonsCortexBnmp($response));
            }
        }

        if ($request->filled('cpf')) {
            $response = $bnmpCortex->personSearchBnmpCpf($request->cpf);
            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->getPersonCortexBnmp($response[0]));
            }
        }

        return $data->unique();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function nexus(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::table('persons')->when($request->name, function ($query, $name) {
            $nameUpper = Str::upper($name);
            $nameAscii = Str::ascii($nameUpper);
            return $query->where(function($q) use ($nameUpper, $nameAscii) {
                $q->where('name', 'ilike', '%' . $nameUpper . '%')
                  ->orWhere('name', 'ilike', '%' . $nameAscii . '%')
                  ->orWhere('nickname', 'ilike', '%' . $nameUpper . '%')
                  ->orWhere('nickname', 'ilike', '%' . $nameAscii . '%');
            });
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->rg, function ($query, $rg) {
            return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
        })->when($request->father, function ($query, $father) {
            $fatherUpper = Str::upper($father);
            $fatherAscii = Str::ascii($fatherUpper);
            return $query->where(function($q) use ($fatherUpper, $fatherAscii) {
                $q->where('father', 'ilike', '%' . $fatherUpper . '%')
                  ->orWhere('father', 'ilike', '%' . $fatherAscii . '%');
            });
        })->when($request->mother, function ($query, $mother) {
            $motherUpper = Str::upper($mother);
            $motherAscii = Str::ascii($motherUpper);
            return $query->where(function($q) use ($motherUpper, $motherAscii) {
                $q->where('mother', 'ilike', '%' . $motherUpper . '%')
                  ->orWhere('mother', 'ilike', '%' . $motherAscii . '%');
            });
        })->when($request->birth_date, function ($query, $birthDate) {
            return $query->where('birth_date', StrHerlper::convertDateToEnUs($birthDate));
        })->limit(50)->select([
            'id',
            'name',
            'cpf',
            'mother',
            'father',
            DB::raw('to_char(birth_date::date, \'dd/mm/yyyy\') as birth_date')
        ])->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function cortex(Request $request): Collection
    {
        $excludedFields = $request->except('father', 'rg', 'lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $cortex = new CortexApi();
        $data = collect([]);
        $response = null;

        if ($request->filled('name', 'mother')) {
            $response = $cortex->personSearchNameAndMother($request->name, $request->mother);

            if ($response && !array_key_exists('error', $response)) {
                foreach ($response as $value) {
                    $data->push($this->getPersonCortex($value));
                }
            }
        }

        if ($request->filled('name', 'birth_date') && !$response) {
            $response = $cortex->personSearchNameAndBirthDate($request->name, $request->birth_date);

            if ($response && !array_key_exists('error', $response)) {
                foreach ($response as $value) {
                    $data->push($this->getPersonCortex($value));
                }
            }
        }

        if ($request->cpf) {
            $response = $cortex->personSearchCPF($request->cpf);

            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->getPersonCortex($response));
            }
        }

        return $data->unique();
    }

    /**
     * @param array $value
     * @return Person
     */
    private function getPersonCortex(array $value): Person
    {
        $person = new Person();
        $person->id = $value['numeroCPF'] ?? '';
        $person->name = ($value['nomeCompleto'] ?? '') . ' - UF: ' . ($value['uf'] ?? '');
        $person->cpf = $value['numeroCPF'] ?? '';
        $person->mother = $value['nomeMae'] ?? '';
        $person->birth_date = $value['dataNascimento'] ?? '';

        return $person;
    }

    /**
     * @param array $value
     * @return Person
     */
    private function getPersonCortexBnmp(array $value): Person
    {
        $person = new Person();

        if ($value) {
            $person->id = $value['idpessoa'] ?? '';
            $person->name = ($value['nome'] ?? '') . " ( " . ($value['statusPessoa'] ?? '') . " )";
            $person->mother = $value['nomeMae'] ?? '';
            $person->birth_date = $value['dataNascimento'] ?? '';
        }

        return $person;
    }

    /**
     * @param $base
     * @param $id
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function show($base, $id): Factory|View|Application
    {
        $request = $this->getRequestSession();
        $service = new PersonSearchService();
        $person = $service->$base($id);

        return view('search.person.index', compact('base', 'person', 'request'));
    }


    /**
     * @param Request $request
     * @return string
     */
    protected function generateInputHash(Request $request): string
    {
        $cacheKey = implode('|', $request->options) . '|' . implode('|', $request->except('_token', 'options'));
        return hash('md5', $cacheKey);
    }

    /**
     * @param array $inputs
     * @return array
     */
    protected function inputFilterRequestEmpty(array $inputs): array
    {
        return Arr::where($inputs, function ($value) {
            return !empty($value);
        });
    }

    /**
     * @return Request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRequestSession(): Request
    {
        $requestSearch = session()->get('request_search');
        return $requestSearch ? new Request($requestSearch) : new Request(['options' => []]);
    }

    /**
     * Gera relatório completo da pessoa
     * @param int $id
     * @return View
     */
    public function report($id): View
    {
        $person = Person::with([
            'address', 
            'telephones', 
            'emails', 
            'socials', 
            'images',
            'companies',
            'vehicles',
            'vinculoOrcrims',
            'pcpas',
            'tjs',
            'armas',
            'rais',
            'bancarios',
            'docs'
        ])->findOrFail($id);

        return view('search.person.report', compact('person'));
    }
}
