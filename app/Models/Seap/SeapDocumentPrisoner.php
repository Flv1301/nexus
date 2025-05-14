<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of DocumentPrisoner
 *
 * @author 55919
 */
class SeapDocumentPrisoner extends Model{
    //put your code here

    protected $connection = "seap";
    protected $table = "preso_documento";

    public static function getDocumentByPrisioner($id){

       return SeapDocumentPrisoner::query()->where('id_preso','=',$id)->get();

     }


}

