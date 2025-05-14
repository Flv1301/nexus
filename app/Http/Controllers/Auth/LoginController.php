<?php

namespace App\Http\Controllers\Auth;

use App\Events\LogUserAccessEvent;
use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     *  Load page login
     * @return View
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function authenticate(Request $request): Redirector|RedirectResponse|Application
    {
        $location = empty($request->latitude) || empty($request->longitude);

        if (env('APP_ENV') === 'production' && $location) {
            toast('Por favor, habilitar a localização no navegador', 'info');

            return back()->withInput();
        }

        $credentials = $request->only('email', 'password', 'g-recaptcha-response');
        $validator = $this->validator($credentials);
        $remember = $request->input('remember', false);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        unset($credentials['g-recaptcha-response']);

        if (Auth::attempt($credentials, $remember)) {
            LogHelper::logActivity('login', 'Login', 'Login de Usuário: ' . $request->email);
            LogUserAccessEvent::dispatch($request);

            return redirect('/');
        }

        LogHelper::logActivity('login', 'Login', "Credenciais do Usuário {$request->email} Inválidas", $validator);
        $validator->errors()->add('password', 'e-mail e/ou senha inválido(s)');

        return redirect()->route('login')->withErrors($validator)->withInput();
    }

    /**
     * Logout Session User
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        activity()->useLog('Logout')->log('Logout de Usuário');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cache::flush();
        return redirect('/login');
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
            'g-recaptcha-response' => [
                'required' => Rule::requiredIf(env('APP_ENV') === 'production'),
                'captcha'
            ],
        ]);
    }
}
