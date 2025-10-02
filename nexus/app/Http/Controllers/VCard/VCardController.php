<?php

namespace App\Http\Controllers\VCard;

use App\Http\Controllers\Controller;
use App\Models\Views\ViewVCard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class VCardController extends Controller
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
        return view('vcard.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $request): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.ler')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $request->validate(['number_or_name' => 'required']);
        $user = Auth::user();
        try {
            $vcards = ViewVCard::where(function ($query) use ($request) {
                    $query->where('fullname', 'ilike', '%' . $request->number_or_name . '%')
                        ->orWhere('number', 'ilike', '%' . $request->number_or_name . '%')
                        ->orWhere('name', 'ilike', '%' . $request->number_or_name . '%');
                })
                ->get();

            return view('vcard.index', compact('vcards'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema!', 'error');

            return back();
        }
    }
}
