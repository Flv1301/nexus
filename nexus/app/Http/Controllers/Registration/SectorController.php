<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectorRequest;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SectorController extends Controller
{

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('setor')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        $sectors = Sector::all();
        return view('sector.index', compact('sectors'));
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('setor.cadastrar')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        $unitys = Unity::all();
        return view('sector.create', compact('unitys'));
    }

    /**
     * @param SectorRequest $request
     * @return RedirectResponse
     */
    public function store(SectorRequest $request): RedirectResponse
    {
        try {
            Sector::create($request->only(['name', 'unity_id']));
            toast('Setor cadastrado com sucesso!', 'success');
            return redirect()->route('sectors');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível cadastrar o setor!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('setor.atualizar')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        try {
            $sector = Sector::findOrFail($id);
            $unitys = Unity::all();

            return view('sector.edit', compact('unitys', 'sector'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return back()->withInput();
        }
    }

    /**
     * @param SectorRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(SectorRequest $request, $id): RedirectResponse
    {
        try {
            $unity = Sector::find($id);
            $unity->update($request->only(['name', 'unity_id']));
            toast('Setor atualizado com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível atualizar o setor!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('setor.excluir')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        try {
            $unity = Sector::findOrFail($id);
            $unity->delete();
            toast('Setor excluído com sucesso', 'success');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Setor vinculado, exclusão não permitida!', 'info');
            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function users(Request $request): JsonResponse
    {
        if (!Gate::allows('setor.usuario.pesquisar')) {
            return response()->json(['error' => 'Sem permissão!'], 403);
        }
        $users = User::where('sector_id', $request->id)->whereNotIn('id', [1, Auth::id()])->get();
        return response()->json(['users' => $users]);
    }
}
