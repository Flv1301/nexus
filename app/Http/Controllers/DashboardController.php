<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers;

use App\Models\Cases\CaseProcedure;
use App\Models\Cases\Cases;
use App\Services\CaseService;
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
        $casesIds = CaseService::getCaseUserIds($user);
        $cases = Cases::whereIn('id', $casesIds)->where('status', '!=', 'CONCLUIDO')->count();
        $procedures = CaseProcedure::where('user_id', $user->id)->where('status', '!=', 'CONCLUIDO')->count();
        $notifications = $user->notifications->where('read_at', '=', null)->count();

        return view('dashboard', compact('user', 'cases', 'procedures', 'notifications'));
    }
}
