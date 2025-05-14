<?php

namespace App\Http\Controllers\Qualify;

use App\Models\Dpa\Dpa;
use Illuminate\Http\Request;

class DPAController extends \App\Http\Controllers\Controller
{
    public function show(Request $request){
        //$prisoner = MovePrisoner::getMovesByPrisioner($request->input("id"));

        $person = Dpa::getDpaById($request->input("id"));

        return view('qualify.dpa.show')->with("person",$person);
    }

}
