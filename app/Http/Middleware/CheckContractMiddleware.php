<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckContractMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            $user = Auth::user();
            $contracts = $user->accessContract;

            if (!$contracts->count() or $contracts->where('agree', false)->count()) {
                return redirect()->route('user.access.contract.show');
            }
        }

        return $next($request);
    }
}
