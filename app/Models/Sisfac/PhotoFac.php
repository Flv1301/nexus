<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Sisfac;

/**
 * Description of PhotoFac
 *
 * @author 55919
 */
class PhotoFac extends \Illuminate\Database\Eloquent\Model{
    //put your code here
     protected $connection = "pgsql2";
    protected $table = "sisfac.pessoa_foto";

    public static function getPhotoById($id){
       
      
     return PhotoFac::query()->where("fk_pessoa","=", $id)->first();
    
      
      
    }
    public static function getAllPhotosById($id){
       
      
     return PhotoFac::query()->where("fk_pessoa","=", $id)->get();
    
      
      
    }
    
    
}
