<?php

namespace App\Http\Middleware;

use App\Enums\TypeDocumentEnum;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Authenticatable;
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

        if (!$this->checkContract($user) && env('TERM_RESPONSIBILITY')) {
            return redirect()->route('user.access.contract.show');
        }

        return $next($request);
    }

    /**
     * @param Authenticatable $user
     * @return bool
     */
    private function checkContract(Authenticatable $user): bool
    {
        $contracts = $user->documents;
        return $contracts->where('agree', true)->where('type', TypeDocumentEnum::TDR->value)->count();
    }
}
