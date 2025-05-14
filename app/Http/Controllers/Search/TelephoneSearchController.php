<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/07/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Search;

use App\Models\Data\Telephone;
use App\Models\Dpa\Dpa;
use App\Models\Equatorial;
use App\Models\Sisp\BopEnv;
use App\Models\Srh\Srh;
use App\Services\PersonSearchService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TelephoneSearchController extends PersonSearchController
{
    /**
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Factory|View|Application
    {
        $bases = $this->getCachedBases();
        $requestSearch = session()->get('request_search');
        $request = $requestSearch ? new Request($requestSearch) : new Request(['options' => []]);

        return view('search.telephone.index', compact('bases', 'request'));
    }

    /**
     * @return array|mixed
     */
    private function getCachedBases(): mixed
    {
        $id = Auth::id();

        if (Cache::has('telephone_search_' . $id)) {
            return Cache::get('telephone_search_' . $id);
        }

        return [];
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $request): View|Factory|RedirectResponse|Application
    {
        $request->validate(['telephone' => 'required'], ['telephone.required' => 'Campo telefone é obrigatório.']);
        if (!$request->filled('options')) {
            toast('Por favor, selecione alguma base de dados para realizar a pesquisa.', 'info');
            return back()->withInput();
        }

        try {
            $id = Auth::id();
            $inputHash = $this->generateInputHash($request);

            if (Session::has('hashInput') && Session::get('hashInput') !== $inputHash) {
                Cache::forget('telephone_search_' . $id);
            }

            $bases = cache()->remember('telephone_search_' . $id, now()->addMinute(5), function () use ($request) {
                $bases = [];
                foreach ($request->options as $option) {
                    $bases[$option] = $this->$option($request);
                }

                return $bases;
            });
            session()->put('hashInput', $inputHash);
            session()->put('request_search', $request->except('_token'));

            return view('search.telephone.index', compact('bases', 'request'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível realizar a busca', 'error');

            return back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function person(Request $request): Collection
    {
        $query = Telephone::query();
        $query->where('telephone', 'like', "%$request->telephone%");

        if ($request->has('ddd')) {
            $query->where('ddd', 'like', "%$request->ddd%");
        }

        $persons = $query->with(['persons:id,name,cpf,mother,birth_date'])->limit(50)->get();
        return $persons->pluck('persons')->flatten();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function sisp(Request $request): Collection
    {
        return BopEnv::where('contato', 'like', "%{$request->ddd}{$request->telephone}%")
            ->select([
                'bopenv_bop_key as id',
                'cpf',
                'nm_envolvido as name',
                'mae as mother',
                DB::raw("to_char(nascimento::date, 'dd/mm/yyyy') as birth_date")
            ])
            ->distinct()
            ->limit(50)
            ->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function seap(Request $request): Collection
    {
        return DB::connection('seap')->table('seap.preso as pr')
            ->leftJoin('seap.preso_documento as doc', 'pr.id_preso', '=', 'doc.id_preso')
            ->where('pr.telefone_numero', 'like', "%{$request->telephone}%")
            ->when($request->ddd, function ($query) use ($request) {
                return $query->where('pr.telefone_ddd', 'like', "%{$request->ddd}%");
            })
            ->selectRaw(
                '
            pr.id_preso as id,
            doc.cpf_numero as cpf,
            pr.preso_nome as name,
            pr.presofiliacao_mae as mother,
            pr.presofiliacao_pai as father,
            to_char(pr.preso_datanascimento, \'dd/mm/yyyy\') as birth_date'
            )
            ->distinct()
            ->limit(50)
            ->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function seap_visitante(Request $request): Collection
    {
        return DB::connection('seap')->table('seap.visitante as vt')
            ->leftJoin('seap.visitante_documento as doc', 'vt.id_visitante', '=', 'doc.id_visitante')
            ->where('telefone_numero', 'like', "%$request->telephone%")
            ->selectRaw(
                'vt.id_visitante as id,
                doc.visitantedocumento_numero as cpf,
                vt.visitante_nome as name,
                vt.visitante_mae as mother,
                vt.visitante_pai as father,
                to_char(vt.visitante_datanascimento, \'dd/mm/yyyy\') as birth_date'
            )
            ->distinct()
            ->limit(50)
            ->get();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function dpa(Request $request): Collection
    {
        $searchTerm = $request->ddd . $request->telephone;
        return Dpa::where('propr_telefone', 'like', "%{$searchTerm}%")->orWhere(
            'responsavel_telefone',
            'like',
            "%{$searchTerm}%"
        )
            ->select([
                'id_proprietario as id',
                'cpf_propr as cpf',
                'cnpj_prop as cnpj',
                'rg_propr as rg',
                'nm_proprietario as name',
                DB::raw("'' as mother"),
                DB::raw("'' as birth_date")
            ])
            ->distinct()
            ->limit(50)
            ->get();
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
        $request = new Request(session()->get('request_search'));
        $service = new PersonSearchService();

        if ($base !== 'sisp') {
            $person = $service->$base($id);

            return view('search.telephone.index', compact('base', 'person', 'request'));
        }

        $bops = $service->$base($id);

        return view('search.telephone.index', compact('base', 'bops', 'request'));
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function srh(Request $request): Collection
    {
        $searchTerm = $request->ddd . $request->telephone;

        return Srh::where(function ($query) use ($searchTerm) {
            $query->where('celular', 'like', '%' . $searchTerm . '%')->orWhere(
                'telefone',
                'like',
                '%' . $searchTerm . '%'
            );
        })
            ->select([
                'id_servidor as id',
                'cpf',
                'rg',
                'nome_servidor as name',
                DB::raw("to_char(nascimento::date, 'dd/mm/yyyy') as birth_date"),
                DB::raw("'' as mother"),
            ])
            ->get();
    }

    public function equatorial(Request $request): Collection
    {
        $searchTerm = $request->ddd . $request->telephone;
        return Equatorial::where('telefone', 'like', "%{$searchTerm}%")
            ->select([
                'id',
                'nome as name',
                'cpf'
            ])
            ->get();
    }
}
