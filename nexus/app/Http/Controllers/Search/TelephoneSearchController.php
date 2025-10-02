<?php

namespace App\Http\Controllers\Search;

use App\Models\Data\Telephone;
use App\Services\PersonSearchService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
}
