<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnityRequest;
use App\Models\Data\Address;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UnityController extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('unidade')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        $unitys = Unity::all();
        return view('unity.index', compact('unitys'));
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('unidade.cadastrar')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        $address = new Address();
        return view('unity.create', compact('address'));
    }

    /**
     * @param UnityRequest $resquest
     * @return RedirectResponse
     */
    public function store(UnityRequest $resquest): RedirectResponse
    {
        try {
            $address = Address::create($resquest->except('name', 'contact'));
            $resquest->request->add(['address_id' => $address->id]);
            Unity::create($resquest->only(['name', 'contact', 'address_id']));
            toast('Unidade cadastrada com sucesso!', 'success');
            return redirect()->route('unitys');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível cadastrar a unidade.', 'error');

            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('unidade.atualizar')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }
        try {
            $unity = Unity::findOrFail($id);
            $address = $unity->address()->first() ?? new Address();
            return view('unity.edit', ['unity' => $unity, 'address' => $address]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return back();
        }
    }

    /**
     * @param UnityRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UnityRequest $request, $id): RedirectResponse
    {
        try {
            $unity = Unity::find($id);
            if ($unity->address_id) {
                $address = Address::find($unity->address_id);
                $address->update($request->except(['name', 'contact']));
            } else {
                $address = Address::create($request->except(['name', 'contact']));
                $request->request->add(['address_id' => $address->id]);
            }
            $unity->update($request->only(['name', 'contact', 'address_id']));
            toast('Unidade atualizada com sucesso!', 'success');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível atualizar a unidade!', 'error');

            return back();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('unidade.excluir')) {
            toast('Sem permissão!', 'info');

            return redirect()->route('dashboard');
        }
        try {
            $unity = Unity::findOrFail($id);
            $unity->delete();
            toast('Unidade excluída com sucesso', 'success');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Unidade vinculada, exclusão não permitida!', 'info');

            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sectors(Request $request): JsonResponse
    {
        $sectors = Sector::where('unity_id', $request->id)->get();

        return response()->json(['sectors' => $sectors]);
    }
}
