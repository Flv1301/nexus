<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * @param $request
     * @return string|void|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @param $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards): mixed
    {
        $this->authenticate($request, $guards);
        $user = Auth::user();

        if (!$user->status) {
            Auth::logout();
            return back()->with('info', 'Conta bloqueada!')->withInput();
        }

        return $next($request);
    }
}
