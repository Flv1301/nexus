<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\User;

use App\Enums\CodeControllerUserEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Data\Address;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRegisterController extends Controller
{
    /**
     * @var string
     */
    protected string $redirectTo = '/';

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('usuario')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $users = User::with(['unity', 'sector', 'documents'])->get();
        $users = $users->except(1);
        return view('auth.index', compact('users'));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('usuario.ler')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $user = User::find($id);
        $address = new Address();
        $address->fill([
            'address' => $user->address,
            'number' => $user->number,
            'district' => $user->district,
            'city' => $user->city,
            'state' => $user->state,
            'uf' => $user->uf,
            'complement' => $user->complement,
            'reference_point' => $user->reference_point,
            'code' => $user->code
        ]);
        return view('auth.show', compact('user', 'address'));
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('usuario.cadastrar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $user = new User();
        $address = new Address();
        $unitys = Unity::all();
        $sectors = [];
        $roles = Role::all()->except(1);
        $permissions = Permission::all();
        return view('auth.create', compact(['unitys', 'sectors', 'roles', 'permissions', 'user', 'address']));
    }

    /**
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        try {
            $request->validated();
            $userData = $request->all();
            $userData['password'] = bcrypt($userData['password']);
            $userData['user_creator'] = Auth::user()->name;
            $userData['code_controller'] = CodeControllerUserEnum::CONCLUIDO->name;
            $user = User::create($userData);
            $user->assignRole($request->input('role'));
            if ($request->has('permissions')) {
                $user->syncPermissions($request->input('permissions'));
            }
            toast('Usuário cadastrado com sucesso!', 'success');
            return redirect()->route('users');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível realizar o cadastro!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('usuario.atualizar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        try {
            $user = User::findOrFail($id);
            $unitys = Unity::all();
            $sectors = Sector::where('unity_id', $user->unity_id)->get();
            $roles = Role::all();
            $permissionsKey = $user->permissions->modelKeys();
            $permissions = Permission::all();
            $address = new Address();
            $address->fill([
                'address' => $user->address,
                'number' => $user->number,
                'district' => $user->district,
                'city' => $user->city,
                'state' => $user->state,
                'uf' => $user->uf,
                'complement' => $user->complement,
                'reference_point' => $user->reference_point,
                'code' => $user->code
            ]);
            return view('auth.edit', compact(
                    'user',
                    'address',
                    'unitys',
                    'sectors',
                    'roles',
                    'permissions',
                    'permissionsKey',
                )
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível recuperar o usuário!', 'error');
            return back();
        }
    }

    /**
     * @param RegisterRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(RegisterRequest $request, $id): RedirectResponse
    {
        try {
            $request->validated();
            $user = User::findOrFail($id);
            $user->fill($request->except('password'));

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->coordinator = $request->boolean('coordinator', false);
            $user->status = $request->boolean('status', false);
            $user->user_update = Auth::user()->name;
            $user->code_controller = 'CONCLUIDO';
            $user->update();
            $user->syncRoles([$request->input('role')]);
            $user->syncPermissions($request->input('permissions'));
            toast('Usuário atualizado com sucesso', 'success');
            return back()->withInput();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast(
                'Erro de sistema. Não foi possível atualizar o usuário',
                'error'
            );
            return back()->withInput();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('usuario.excluir')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        try {
            $user = User::findOrFail($id);
            $user->delete();
            toast('Usuário deletado com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível excluir o usuário!', 'error');
            return back();
        }
    }
}
