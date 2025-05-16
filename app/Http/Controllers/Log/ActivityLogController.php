<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $users = User::all()->except(1);
        $logNames = Activity::distinct('log_name')->pluck('log_name');
        return view('log.index', compact('users', 'logNames'));
    }

    public function search(Request $request): Factory|View|Application
    {
        $logs = Activity::orderBy('created_at', 'desc')
            ->when($request->filled('log_name'), function ($query) use ($request) {
                return $query->where('log_name', $request->input('log_name'));
            })
            ->when($request->filled('event'), function ($query) use ($request) {
                return $query->where('event', $request->input('event'));
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                return $query->where('causer_id', $request->input('user_id'));
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                return $query->whereDate(
                    'created_at',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $request->input('from'))->format('Y-m-d')
                );
            })
            ->when($request->filled('to'), function ($query) use ($request) {
                return $query->whereDate(
                    'created_at',
                    '<=',
                    Carbon::createFromFormat('d/m/Y', $request->input('to'))->format('Y-m-d')
                );
            })->get();
        $users = User::all()->except(1);
        $logNames = Activity::distinct('log_name')->pluck('log_name');
        return view('log.index', compact('logs', 'logNames', 'users'));
    }

    public function show($id)
    {
        $log = Activity::find($id);
        return view('log.show', compact('log'));
    }
}
