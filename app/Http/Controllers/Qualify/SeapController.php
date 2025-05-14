<?php

namespace App\Http\Controllers\Qualify;

use App\Http\Controllers\Controller;
use App\Models\Seap\Seap;
use Illuminate\Http\Request;

class SeapController extends Controller
{
    public function indexPost(Request $request)
    {
        $seap = new Seap();

        $arrayteste = [];

        if ($request->filled("inputName")) {
            array_push($arrayteste, ['preso_nome', 'ilike', '%' . $request->input("inputName") . '%']);
        }
        if ($request->filled("alcunha")) {
            array_push($arrayteste, ['presoalcunha_descricao', 'ilike', '%' . $request->input("alcunha") . '%']);
        }

        if ($request->filled("nomeMae")) {
            array_push($arrayteste, ['presofiliacao_mae', 'ilike', '%' . $request->input("nomeMae") . '%']);
        }
        if ($request->filled("nomePai")) {
            array_push($arrayteste, ['presofiliacao_pai', 'ilike', '%' . $request->input("nomePai") . '%']);
        }
        if ($request->filled("cidade")) {
            array_push($arrayteste, ['municipio_nome', 'ilike', '%' . $request->input("cidade") . '%']);
        }

        if ($request->filled("id_preso")) {
            array_push($arrayteste, ['id_preso', '=', $request->input("id_preso")]);
        }

        $presos = Seap::query()->where($arrayteste)->limit(50)->get();

        return view('qualify.seap.index')->with("presos", $presos);

        $presos = Seap::query()->where($arrayteste)->limit(50)->get();

        return view('qualify.seap.index')->with("presos", $presos);
    }

    public function index()
    {
        return view('qualify.seap.index');
    }


    public function show(Request $request)
    {
        //$prisoner = MovePrisoner::getMovesByPrisioner($request->input("id"));

        $prisoner = Seap::getPrisonerById($request->input("id"));
        return view('qualify.seap.show')->with("prisoner", $prisoner);
    }

    public function mapa(Request $request)
    {
        return view('qualify.seap.mapa')->with("id", $request->input("id_preso"));
    }



}
