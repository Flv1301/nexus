<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Models\Data\Address;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function show(): Factory|View|Application
    {
        $user = User::find(Auth::id());
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
            'code' => $user->code,
        ]);
        return view('perfil.show', compact('user', 'address'));
    }

    /**
     * @return Application|Factory|View
     */
    public function password(): Factory|View|Application
    {
        return view('perfil.reset');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password_current' => 'required',
            'password' => 'required|min:6|string|confirmed'
        ]);
        if (!Hash::check($request->password_current, $request->user()->password)) {
            return back()->withErrors([
                'password_current' => ['The provided password does not match our records.']
            ])->withInput();
        }
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();
        toast('Senha atualizada com sucesso!', 'success');
        return back();
    }
}
