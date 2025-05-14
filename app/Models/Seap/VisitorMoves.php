<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of VisitorMoves
 *
 * @author 55919
 */
class VisitorMoves extends Model{
    //put your code here
     protected $connection = "seap";
    protected $table = "visitante_movimentacao";



    public static function getVisitorsByPrisoner($id){
      $visitors = VisitorMoves::query()->where('id_preso', '=', $id)->distinct()->get();
      return $visitors;
    }

}
