<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Views\ViewCaseFileWhatsappCall;
use App\Models\Views\ViewCaseFileWhatsappMessage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.ler')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        return view('search.ticket.index');
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function search(Request $request): Factory|View|Application
    {
        $request->validate(['numberOrIp' => 'required|string']);
        $user = Auth::user();
        $tickets = [];

        $tickets['calls'] = ViewCaseFileWhatsappCall::where('sector_id', $user->sector_id)
            ->when($request->numberOrIp, function ($query, $from) use ($request) {
                if ($request->column === 'T') {
                    $query->Where(function ($query) use ($from) {
                        $query->where('from', 'like', "%$from%")->orWhere('to', 'like', "%$from%")->orWhere(
                            'from_ip',
                            'like',
                            "%$from%"
                        );
                    });
                }
                if ($request->column === 'O') {
                    $query->Where(function ($query) use ($from) {
                        $query->where('from', 'like', "%$from%")->orWhere('from_ip', 'like', "%$from%");
                    });
                }
                if ($request->column === 'D') {
                    $query->Where(function ($query) use ($from) {
                        $query->where('to', 'like', "%$from%")->orWhere('from_ip', 'like', "%$from%");
                    });
                }
            })
            ->when($request->date_start, fn($query, $dateStart) => $query->where(
                'timestamp',
                '>=',
                Carbon::createFromFormat('d/m/Y', $dateStart)->startOfDay()->toDateTimeString()
            ))
            ->when($request->date_end, fn($query, $date_end) => $query->where(
                'timestamp',
                '<=',
                Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay()->toDateTimeString()
            ))
            ->limit(1000)
            ->get();

        $tickets['messages'] = ViewCaseFileWhatsappMessage::where('sector_id', $user->sector_id)
            ->when($request->numberOrIp, function ($query, $input) use ($request) {
                if ($request->column === 'T') {
                    $query->where(function ($query) use ($input) {
                        $query->where('sender', $input)
                            ->orWhere('recipients', 'like', "%$input%")
                            ->orWhere('group_id', 'like', "%$input%")
                            ->orWhere('sender_ip', 'like', "%$input%");
                    });
                }
                if ($request->column === 'O') {
                    $query->where(function ($query) use ($input) {
                        $query->where('sender', 'like', "%$input%")->orWhere('sender_ip', 'like', "%$input%");
                    });
                }
                if ($request->column === 'D') {
                    $query->where(function ($query) use ($input) {
                        $query->Where('recipients', 'like', "$input%")->orWhere(
                            'sender_ip',
                            'like',
                            "%$input%"
                        );
                    });
                }
            })
            ->when($request->date_start, function ($query, $dateStart) {
                $query->whereDate(
                    'timestamp',
                    '>=',
                    Carbon::createFromFormat('d/m/Y', $dateStart)->startOfDay()->toDateTimeString()
                );
            })
            ->when($request->date_end, function ($query, $date_end) {
                $query->whereDate(
                    'timestamp',
                    '<=',
                    Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay()->toDateTimeString()
                );
            })
            ->limit(1000)
            ->get();

        return view('search.ticket.index', compact('tickets'));
    }

}
