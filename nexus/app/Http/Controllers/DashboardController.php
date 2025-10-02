<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $user = Auth::user();
//        $casesIds = CaseService::getCaseUserIds($user);
//        $cases = Cases::whereIn('id', $casesIds)->where('status', '!=', 'CONCLUIDO')->count();
//        $procedures = CaseProcedure::where('user_id', $user->id)->where('status', '!=', 'CONCLUIDO')->count();

        return view('dashboard', compact('user'));
    }
}
