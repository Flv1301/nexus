<?php

namespace App\Http\Controllers\RolePemission;

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $permissionsCreate = [];
        $permissions = Permission::all();
        foreach (PermissionsEnum::cases() as $permission) {
            if (!$permissions->contains(fn($v) => $v->name === $permission->value)) {
                $permissionsCreate[] = $permission->value;
            }
        }
        return view(
            'permission.index',
            compact(
                'permissions',
                'permissionsCreate'
            )
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate(['name' => 'required|unique:permissions,name']);
            Permission::create($request->only('name'));
            toast('permissão cadastrada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast(
                'Erro de sistema. Não foi possível cadastrar a permissão!',
                'error'
            );
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
            $permission = Permission::findById($id);
            $permission->delete();
            toast('Permissão deletada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast(
                'Erro de sistema. Não foi possível excluir a permissão!',
                'error'
            );
            return back();
        }
    }
}
