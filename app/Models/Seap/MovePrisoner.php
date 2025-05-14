<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of MovePrisoner
 *
 * @author 55919
 */
class MovePrisoner extends Model{
    //put your code here

    protected $connection = "seap";
    protected $table = "preso_movimentacao";

    public static function getMovesByPrisioner($id){

       return  MovePrisoner::query()->where('id_preso','=',$id)->orderBy('movimentacao_data', 'asc')->get();

     }


}
