<?php

namespace App\Models\Sisfac;


use Illuminate\Database\Eloquent\Model;

/**
 * Description of Sisfac
 *
 * @author 55919
 */
class Sisfac extends Model{
    //put your code here
    protected $connection = "pgsql2";
    protected $table = "sisfac.pessoa";

     public static function getFacById($id){
        return Sisfac::query()->where('id', '=',$id)->first();
     }
     public static function getFacByCPF($cpf){
        return Sisfac::query()->where('documento', 'ilike',$cpf)->first();
     }
     public static function getFacByMotherAndName($name, $mother){
        return Sisfac::query()->where('nome', 'ilike',$name)->where('mae','ilike',$mother)->first();
     }
     public static function getFacByNameAndBirth($name, $birth){
        return Sisfac::query()->where('nome', 'ilike',$name)->whereRaw('DATE(dt_nascimento) = ?',$birth)->first();
     }

}
