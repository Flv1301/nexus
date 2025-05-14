<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Qualify;

use Illuminate\Http\Request;

/**
 * Description of SisfacController
 *
 * @author 55919
 */
class SisfacController extends \App\Http\Controllers\Controller
{
    //put your code here

    public function indexPost(Request $request)
    {
        $arrayteste = [];

        if ($request->filled("inputName")) {
            array_push($arrayteste, ['nome', 'ilike', '%' . $request->input("inputName") . '%']);
        }
        if ($request->filled("nomeMae")) {
            array_push($arrayteste, ['mae', 'ilike', '%' . $request->input("nomeMae") . '%']);
        }
        if ($request->filled("nomePai")) {
            array_push($arrayteste, ['pai', 'ilike', '%' . $request->input("nomePai") . '%']);
        }
        if ($request->filled("alcunha")) {
            array_push($arrayteste, ['alcunha', 'ilike', '%' . $request->input("alcunha") . '%']);
        }
        if ($request->filled("infopen")) {
            array_push($arrayteste, ['infopen', '=', $request->input("infopen")]);
        }

        if ($request->filled("cidade")) {
            array_push($arrayteste, ['area_atuacao', 'ilike', '%' . $request->input("cidade") . '%']);
        }

        if ($request->filled("orcrim")) {
            array_push($arrayteste, ['orcrim', '=', $request->input("orcrim")]);
        }

        if ($request->filled("documento")) {
            array_push($arrayteste, ['documento', 'ilike', '%' . $request->input("documento") . '%']);
        }
        if ($request->filled("cargo")) {
            array_push($arrayteste, ['cargo', 'ilike', '%' . $request->input("cargo") . '%']);
        }

        if ($request->filled("documento")) {
            array_push($arrayteste, ['documento', 'ilike', '%' . $request->input("documento") . '%']);
        }
        if ($request->filled("cargo")) {
            array_push($arrayteste, ['cargo', 'ilike', '%' . $request->input("cargo") . '%']);
        }

        $facs = \App\Models\Sisfac\Sisfac::query()->where($arrayteste)->limit(50)->get();

        return view('qualify.sisfac.index')->with("facs", $facs);
//        $facs = \App\Models\Sisfac\Sisfac::query()->where($arrayteste)->limit(50)->get();
//
//        return view('qualify.sisfac.index')->with("facs", $facs);
    }

    public function index()
    {
        return view('qualify.sisfac.index');
    }


    public function show(Request $request)
    {
        //$prisoner = MovePrisoner::getMovesByPrisioner($request->input("id"));

        $fac = \App\Models\Sisfac\Sisfac::getFacById($request->input("id"));
        return view('qualify.sisfac.show')->with("fac", $fac);
    }
}
