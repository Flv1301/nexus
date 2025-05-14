<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Search;

use App\APIs\CacadorRoraimaApi;
use App\APIs\CortexApi;
use App\APIs\ProdepaApi;
use App\APIs\SeducAPI;
use App\Helpers\Str as StrHerlper;
use App\Http\Controllers\Controller;
use App\Models\Person\Person;
use App\Services\PersonSearchService;
use Carbon\Carbon;
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

            StrHerlper::asciiRequest($request, $request->except('_token', 'options'));
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
    public function person(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::table('persons')->when($request->name, function ($query, $name) {
            return $query->where('name', 'like', '%' . Str::upper($name) . '%')->orWhere(
                'nickname',
                'like',
                '%' . Str::upper($name) . '%'
            );
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->rg, function ($query, $rg) {
            return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
        })->when($request->father, function ($query, $father) {
            return $query->where('father', 'like', '%' . Str::upper($father) . '%');
        })->when($request->mother, function ($query, $mother) {
            return $query->where('mother', 'like', '%' . Str::upper($mother) . '%');
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
    public function seduc(Request $request): Collection
    {
        $excludedFields = $request->except('rg', 'cpf', 'birth_date', 'lastname', 'options', '_token');

        if (!Arr::whereNotNull($excludedFields)) {
            return collect([]);
        }

        $seduc = new SeducAPI();
        $data = collect([]);

        $response = null;

        if ($request->name && $request->mother && $request->father) {
            $response = $seduc->searchStudents($request->name, $request->mother, $request->father);
        }

        if ($response === null && $request->name && $request->mother) {
            $response = $seduc->searchStudentsWithMother($request->name, $request->mother);
        }

        if ($response === null && $request->name && $request->father) {
            $response = $seduc->searchStudentsWithFather($request->name, $request->father);
        }

        if ($response === null && $request->name) {
            $response = $seduc->searchStudents($request->name);
        }

        if ($response === null && $request->father) {
            $response = $seduc->searchStudentsForFather($request->father);
        }

        if ($response === null && $request->mother) {
            $response = $seduc->searchStudentsForMother($request->mother);
        }

        if ($response && !array_key_exists('error', $response) && !array_key_exists('msg', $response)) {
            $response = collect($response)->except('status');
            $response->each(function ($value) use ($data) {
                $person = new \stdClass();
                $person->id = $value['nome_aluno'] . '|' . $value['nome_mae'];
                $person->name = $value['nome_aluno'];
                $person->mother = $value['nome_mae'];
                $person->father = $value['nome_pai'];
                $person->cpf = $value['cpf'];
                $data->push($person);
            });
        }

        return $data->unique();
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
     * @param Request $request
     * @return Collection
     */
    public function sisp(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'rg', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::connection('sisp')->table('sisp_full.bopenv')->when($request->name, function ($query, $name) {
            return $query->where('nm_envolvido', 'like', '%' . Str::upper($name) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->father, function ($query, $father) {
            return $query->where('mae', 'like', '%' . Str::upper($father) . '%');
        })->when($request->mother, function ($query, $mother) {
            return $query->where('pai', 'like', '%' . Str::upper($mother) . '%');
        })->when($request->birth_date, function ($query, $birthDate) {
            return $query->where('nascimento', StrHerlper::convertDateToEnUs($birthDate));
        })->select(
            [
                'bopenv_bop_key as id',
                'cpf',
                'nm_envolvido as name',
                'mae as mother',
                'pai as father',
                DB::raw("to_char(nascimento::date, 'dd/mm/yyyy') as birth_date")
            ]
        )->distinct()->limit(50)->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function seap(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::connection('seap')->table('seap.preso as pr')
            ->leftJoin('seap.preso_documento as doc', 'pr.id_preso', '=', 'doc.id_preso')
            ->when($request->name, function ($query, $name) {
                return $query->where('pr.preso_nome', 'like', '%' . Str::upper($name) . '%');
            })->when($request->cpf, function ($query, $cpf) {
                return $query->where('doc.cpf_numero', 'like', '%' . Str::upper($cpf) . '%');
            })->when($request->rg, function ($query, $rg) {
                return $query->where('doc.rg_numero', 'like', '%' . Str::upper($rg) . '%');
            })->when($request->father, function ($query, $father) {
                return $query->where('pr.presofiliacao_pai', 'like', '%' . Str::upper($father) . '%');
            })->when($request->mother, function ($query, $mother) {
                return $query->where('pr.presofiliacao_mae', 'like', '%' . Str::upper($mother) . '%');
            })->when($request->birth_date, function ($query, $birthDate) {
                return $query->where('pr.preso_datanascimento', StrHerlper::convertDateToEnUs($birthDate));
            })->selectRaw(
                'pr.id_preso as id,
                doc.cpf_numero as cpf,
                doc.rg_numero as rg,
                pr.preso_nome as name,
                pr.presofiliacao_mae as mother,
                pr.presofiliacao_pai as father,
                to_char(pr.preso_datanascimento, \'dd/mm/yyyy\') as birth_date'
            )->distinct()->limit(50)->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function seap_visitante(Request $request): Collection
    {
        $excludedFields = $request->except('options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return (
        DB::connection('seap')->table('seap.visitante as vt')
            ->leftJoin('seap.visitante_documento as doc', 'vt.id_visitante', '=', 'doc.id_visitante')
            ->when($request->name, function ($query, $name) {
                return $query->where('vt.visitante_nome', 'like', '%' . Str::upper($name) . '%');
            })->when($request->cpf, function ($query, $cpf) {
                return $query->where('doc.visitantedocumento_numero', 'like', '%' . Str::upper($cpf) . '%');
            })->when($request->rg, function ($query, $rg) {
                return $query->where('doc.visitantedocumento_numero', 'like', '%' . Str::upper($rg) . '%');
            })->when($request->father, function ($query, $father) {
                return $query->where('vt.visitante_pai', 'like', '%' . Str::upper($father) . '%');
            })->when($request->mother, function ($query, $mother) {
                return $query->where('vt.visitante_mae', 'like', '%' . Str::upper($mother) . '%');
            })->when($request->birth_date, function ($query, $birthDate) {
                return $query->where('vt.visitante_datanascimento', StrHerlper::convertDateToEnUs($birthDate));
            })->selectRaw(
                'vt.id_visitante as id,
                doc.visitantedocumento_numero as cpf,
                vt.visitante_nome as name,
                vt.visitante_mae as mother,
                vt.visitante_pai as father,
                to_char(vt.visitante_datanascimento, \'dd/mm/yyyy\') as birth_date'
            )->distinct()->limit(50)->get()
        )->unique();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function galton(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::connection('galton')->table('prontuario')->when($request->name, function ($query, $name) {
            return $query->where('nome', 'like', '%' . Str::upper($name) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->rg, function ($query, $rg) {
            return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
        })->when($request->father, function ($query, $father) {
            return $query->where('mae', 'like', '%' . Str::upper($father) . '%');
        })->when($request->mother, function ($query, $mother) {
            return $query->where('pai', 'like', '%' . Str::upper($mother) . '%');
        })->when($request->birth_date, function ($query, $birthDate) {
            return $query->where('data_nascimento', StrHerlper::convertDateToEnUs($birthDate));
        })->selectRaw(
            'prontuario as id,
                    cpf,
                    rg,
                    nome as name,
                    mae as mother,
                    pai as father,
                    to_char(data_nascimento,\'dd/mm/yyyy\') as birth_date'
        )->distinct()->limit(50)->get();
    }

    public function equatorial(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'mother', 'rg', 'father', 'birth_date', 'birth_date', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $teste = DB::table('equatorial')->when($request->name, function ($query, $name) {
            return $query->where('nome', 'like', '%' . Str::upper($name) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->selectRaw(
            '
                     id,
                    cpf,
                    nome as name
                    '
        )->distinct()->limit(50)->get();

        return $teste;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function dpa(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'mother', 'father', 'birth_date', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::connection('dpa')->table('dpa_proprietario')->when($request->name, function ($query, $name) {
            return $query->where('nm_proprietario', 'like', '%' . Str::upper($name) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf_propr', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cnpj_prop', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->rg, function ($query, $rg) {
            return $query->where('rg_propr', 'like', '%' . Str::upper($rg) . '%');
        })->select(
            [
                'id_proprietario as id',
                'cpf_propr as cpf',
                'cnpj_prop as cnpj',
                'rg_propr as rg',
                'nm_proprietario as name'
            ]
        )
            ->addSelect(DB::raw("'' as mother"))->addSelect(DB::raw("'' as father"))
            ->addSelect(DB::raw("'' as birth_date"))->distinct()->limit(50)->get();
    }


    public function polinter(Request $request): Collection
    {

        $excludedFields = $request->except('lastname', 'cpf', 'rg', 'mother', 'father', 'birth_date', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        if (!$request->filled('name')) {
            return collect([]);
        }

        $teste = DB::connection('polinter')->table('mandados')->when($request->name, function ($query, $name) {
            return $query->where('nome', 'like', '%' . Str::upper($name) . '%');
        })->select(
            [
                'id as id',
                'nome as name'
            ]
        )->addSelect(DB::raw("id as id"))->addSelect(DB::raw("'' as birth_date"))->addSelect(DB::raw("'' as mother"))->addSelect(DB::raw("'' as father"))->addSelect(DB::raw("'' as cpf"))->addSelect(DB::raw("'' as rg"))->get();

        return $teste;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function srh(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'mother', 'father', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        return DB::connection('srh')->table('srh')->when($request->name, function ($query, $name) {
            return $query->where('nome_servidor', 'like', '%' . Str::upper($name) . '%');
        })->when($request->cpf, function ($query, $cpf) {
            return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
        })->when($request->rg, function ($query, $rg) {
            return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
        })->when($request->birth_date, function ($query, $birthDate) {
            return $query->where('nascimento', StrHerlper::convertDateToEnUs($birthDate));
        })->select(
            [
                'id_servidor as id',
                'cpf',
                'rg',
                'nome_servidor as name',
                DB::raw("to_char(nascimento::date, 'dd/mm/yyyy') as birth_date")
            ]
        )->addSelect(DB::raw("'' as mother"))->addSelect(DB::raw("'' as father"))->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function prodepa(Request $request): Collection
    {
        $data = collect();

        if ($request->filled('rg')) {
            $response = (new ProdepaApi())->documentSearch($request->rg, $request->cpf);
            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->prodepaPersonReturn($response[0]));
            }
        }

        if ($data->isEmpty() && $request->filled('name', 'lastname', 'birth_date')) {
            $response = (new ProdepaApi())->civilIdentification(
                $request->name,
                $request->lastname,
                Carbon::createFromFormat('d/m/Y', $request->birth_date)->toDateString(),
                $request->input('father', ''),
                $request->input('mother', ''),
                $request->input('rg', '')
            );

            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->prodepaPersonReturn($response[0]));
            }
        }

        if ($data->isEmpty() && $request->filled('name', 'lastname')) {
            $response = (new ProdepaApi())->nameSearch(
                $request->name,
                $request->lastname,
                $request->input('father', ''),
                $request->input('mother', '')
            );

            if ($response && !array_key_exists('error', $response)) {
                foreach ($response as $value) {
                    $data->push($this->prodepaPersonReturn($value));
                }
            }
        }
        return $data->unique();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function cacador(Request $request): Collection
    {
        try {
            $api = new CacadorRoraimaApi();
            $response = $api->personSearch($request);

            if ($response->isOk()) {
                $data = collect($response->getData(true));

                return $data->map(function ($item) {
                    $person = new Person();
                    $person->id = $item['codigo'] ?? '';
                    $person->name = $item['nome'] ?? '';
                    $person->birth_date = $item['nascimento'] ?? '';
                    $person->cpf = $item['cpf'] ?? '';
                    $person->mother = $item['mae'] ?? '';

                    return $person;
                });
            }

            return collect();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }

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

        if ($base !== 'sisp') {
            $person = $service->$base($id);
            return view('search.person.index', compact('base', 'person', 'request'));
        }

        $bops = $service->$base($id);
        return view('search.person.index', compact('base', 'bops', 'request'));
    }

    /**
     * @param $response
     * @return Person
     */
    private function prodepaPersonReturn($response): Person
    {
        $person = new Person();
        $person->id = $response['registroGeral'] ?? '';
        $person->name = ($response['nome'] ?? '') . '  ' . ($response['sobrenome'] ?? '');
        $person->cpf = '';
        $person->mother = $response['mae'] ?? '';
        $person->father = $response['pai'] ?? '';
        $person->naturalness = $response['naturalidade'] ?? '';
        $person->birth_date = isset($response['dataNascimento']) ? Carbon::createFromFormat(
            'dmY',
            $response['dataNascimento']
        )->toDateString() : '';

        return $person;
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
}
