<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\RolePemission;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $roles = Role::all()->except(1);
        $permissions = Permission::all();
        return view('role.index', compact('roles', 'permissions'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $role = Role::findById($id);
        $permissions = $role->getAllPermissions();
        return view('role.show', compact('role', 'permissions'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('role.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        try {
            $name = Str::of($request->input('name'))->studly();
            $role = Role::create(['name' => $name]);
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            toast('Função cadastrada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast(
                'Erro de sistema. Não foi possível cadastrar a função!',
                'error'
            );
            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {
            $role = Role::findById($id);
            $permissionsKey = $role->getAllPermissions()->modelKeys();
            $permissions = Permission::all();
            return view('role.edit', compact('role', 'permissions', 'permissionsKey'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível localizar a função!', 'error');

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
        try {
            $role = Role::findById($id);
            $role->fill($request->only('name'));
            $role->save();
            $role->syncPermissions($request->input('permissions'));
            toast('Função atualizada com sucesso', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível atualizar a função!', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $role = Role::findById($id);
            $role->delete();
            toast('Função deletada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível excluir a função!', 'error');
            return back();
        }
    }
}
