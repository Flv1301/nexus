<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of PhotoVisitor
 *
 * @author 55919
 */
class PhotoVisitor extends Model{
    //put your code here
     protected $connection = "seap";
    protected $table = "visitante_foto";

    public static function getPhotoById($id){


     return PhotoVisitor::query()->where("id_visitante","=", $id)->first();



    }
}
