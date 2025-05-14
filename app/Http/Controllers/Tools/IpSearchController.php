<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/10/2023
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Http\Controllers\Tools;

use App\APIs\IPInfoApi;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IpSearchController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('tools.ipSearch');
    }

    /**
     * @param Request $resquest
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $resquest): View|Factory|RedirectResponse|Application
    {
        $resquest->validate(['ip' => 'required|string']);
        $api = new IPInfoApi();
        $data = $api->IP($resquest->ip);
        if ($data){
            return view('tools.ipSearch', compact('data'));
        }
        toast('Endereço de IP não localizado!', 'info');
        return back()->withInput();
    }
}
