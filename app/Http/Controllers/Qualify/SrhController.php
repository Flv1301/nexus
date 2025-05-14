<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Qualify;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;




/**
 * Description of SispController
 *
 * @author 55919
 */
class SrhController extends Controller{





    //put your code here

     public function indexPost(Request $request){





            $arrayteste = [];

            if($request->filled("inputName")){

                array_push($arrayteste, ['nome_servidor','ilike','%'. $request->input("inputName").'%']);
            }
             if($request->filled("cargo")){

                array_push($arrayteste, ['cargo','ilike','%'.$request->input("cargo").'%']);
            }
            if($request->filled("lotacao")){

                array_push($arrayteste, ['lotacao','ilike','%'.$request->input("lotacao").'%']);
            }
            if($request->filled("dtnasc")){

                array_push($arrayteste, ['nascimento','=',$request->input("dtnasc")]);
            }

             if($request->filled("cpf")){

                array_push($arrayteste, ['cpf','=',$request->input("cpf")]);
            }

            if($request->filled("matricula")){

                array_push($arrayteste, ['matricula','=',$request->input("matricula")]);
            }




           // dd($arrayteste);




          $srhs = \App\Models\Srh\Srh::query()->where($arrayteste)->limit(50)->get();


                return view('qualify.srh.index')->with("srhs",$srhs);






    }

    public function index(){



        return view('qualify.srh.index');
    }


    public function show(Request $request){
//
//       $srh =  BopEnv::getBopEnvById($request->input("id"),$request->input("name"));

      // dd($sisp);
        $srh = \App\Models\Srh\Srh::getEmployeeById($request->input("id"));
        return view('qualify.srh.show')->with("srh",$srh);
    }




}
