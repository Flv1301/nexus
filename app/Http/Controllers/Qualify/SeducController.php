<?php

namespace App\Http\Controllers\Qualify;

use App\APIs\SeducAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeducController extends Controller
{

    public function index(){



        return view('qualify.seduc.index');
    }

    public function show(Request $request){
        $student = '';
        $optionalParameters = '';

        if($request->filled("inputName")){
            if($request->filled("mae")){
                $optionalParameters .= '/nomemae/'.$request->input('mae').'/';
            }
            if($request->filled('pai')){
                $optionalParameters .= '/nomepai/'.$request->input('pai').'/';
            }
           $students = SeducAPI::getStudentByName($request->input('inputName'), $optionalParameters);
           $status = $students['status'] ?? null;
            if($status == 'sucesso'){
                foreach ($students as $student){
                    if ($student == 'sucesso'){
                        continue;
                    }else{

                        return view('qualify.seduc.show')->with("student",$student);
                    }
                }

            }

        }








        return view('qualify.seduc.index')->with('erro','erro');

    }
}
