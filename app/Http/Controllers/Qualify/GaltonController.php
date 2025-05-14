<?php

namespace App\Http\Controllers\Qualify;

use App\Http\Controllers\Controller;
use App\Models\Galton\Galton;
use Illuminate\Http\Request;

class GaltonController extends Controller
{
    public function index(){



        return view('qualify.galton.index');
    }

    public function indexPost(Request $request){

        $arrayteste = [];

        if($request->filled("inputName")){

            array_push($arrayteste, ['nome','ilike','%'. $request->input("inputName").'%']);
        }
        if($request->filled("nomeMae")){

            array_push($arrayteste, ['mae','ilike','%'.$request->input("nomeMae").'%']);
        }
        if($request->filled("nomePai")){

            array_push($arrayteste, ['pai','ilike','%'.$request->input("nomePai").'%']);
        }
        if($request->filled("alcunha")){

            array_push($arrayteste, ['alcunha','ilike','%'.$request->input("alcunha").'%']);
        }

        if($request->filled("cidade")){

            array_push($arrayteste, ['local_detencao','ilike','%'.$request->input("cidade").'%']);
        }


        if($request->filled("rg")){

            array_push($arrayteste, ['rg','=',$request->input("rg")]);
        }
        if($request->filled("cpf")){

            array_push($arrayteste, ['cpf','=',$request->input("cpf")]);
        }

        $galton = Galton::query()->where($arrayteste)->limit(50)->get();

        return view('qualify.galton.index')->with("galton",$galton);


    }

    public function show(Request $request){
        //$prisoner = MovePrisoner::getMovesByPrisioner($request->input("id"));

        $galton = Galton::getGaltonbyProntuario($request->input("id"));
        return view('qualify.galton.show')->with("galton",$galton);
    }


}
