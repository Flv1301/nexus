<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Gi2;
use App\Models\IMEI;
use App\Models\Sisp\Bop;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class IMEIController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view("search.imei.index");
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function search(Request $request): Factory|View|Application
    {
        $request->validate(['imei' => 'required|min:12']);
        $imeis = IMEI::where('imei', 'LIKE', "%$request->imei%")->get();
        return view('search.imei.index', compact('imeis'));
    }

    /**
     * @param $imei
     * @return Factory|View|Application
     */
    public function show($imei): Factory|View|Application
    {
        $imei = IMEI::find($imei);
        $bops = $imei->bop;
        return view('search.imei.show', compact('imei', 'bops'));
    }

    /**
     * @param $bop
     * @param $imei
     * @return Application|Factory|View
     */
    public function bop($bop, $imei): View|Factory|Application
    {
        $bops = Bop::find($bop);
        return view('search.imei.show', compact('bops', 'imei'));
    }

    /**
     * @param $imei
     * @return Application|Factory|View
     */
    public function gi2($imei): View|Factory|Application
    {
        $gi2s = Gi2::where('imei', $imei)->get();
        return view('gi2.show', compact('gi2s'));
    }
}
