<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Qualify;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sisp\BopEnv;


/**
 * Description of SispController
 *
 * @author 55919
 */
class SispController extends Controller{





    //put your code here

     public function indexPost(Request $request){
         set_time_limit(0);





            $arrayteste = [];
            $arrayrelato = [];

            if($request->filled("inputName")){

                array_push($arrayteste, ['nm_envolvido','ilike','%'. $request->input("inputName").'%']);
            }
             if($request->filled("nomeMae")){

                array_push($arrayteste, ['mae','ilike','%'.$request->input("nomeMae").'%']);
            }
            if($request->filled("nomePai")){

                array_push($arrayteste, ['pai','ilike','%'.$request->input("nomePai").'%']);
            }
            if($request->filled("dtnasc")){

                array_push($arrayteste, ['nascimento','=',$request->input("dtnasc")]);
            }

             if($request->filled("cpf")){

                array_push($arrayteste, ['cpf','=',$request->input("cpf")]);
            }

            if($request->filled("cidade")){

                array_push($arrayteste, ['localidade','ilike','%'.$request->input("cidade").'%']);
            }

             if($request->filled("relato")){

                array_push($arrayrelato, ['relato','ilike','%'.$request->input("relato").'%']);

                $reports = \App\Models\Sisp\SispReport::getReport($arrayrelato);

                 return view('qualify.sisp.index')->with("reports",$reports);
            }




           // dd($arrayteste);




              $sisps = BopEnv::query()->distinct('nm_envolvido')->where($arrayteste)->limit(50)->get();


                return view('qualify.sisp.index')->with("sisps",$sisps);






    }

    public function index(){



        return view('qualify.sisp.index');
    }


    public function show(Request $request){

       $sisp =  BopEnv::getBopsEnvByNameAndBirthday($request->input("name"),$request->input("nascimento"));


        return view('qualify.sisp.show')->with("sisp",$sisp);
    }




}
