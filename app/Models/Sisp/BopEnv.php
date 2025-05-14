<?php

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\Model;

class BopEnv extends Model{

    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "bopenv";


    public static function getBopEnvById($id,$name){

        return BopEnv::query()->where('bopenv_bop_key','=',$id)->where('nm_envolvido', 'ilike', $name)->first();
    }

    public static function getBopsEnv($name, $birth){
        $sisps = BopEnv::query()->where('nm_envolvido', 'ilike', $name)->whereRaw('DATE(nascimento) = ?',$birth)->get();
        return $sisps;
    }
    public static function getBopsEnvByCPF($cpf){
        $sisp = BopEnv::query()->where('cpf', '=', $cpf)->first();

        return $sisp;
    }
    public static function getBopsEnvByMotherAndName($name, $mother){
        $sisp = BopEnv::query()->where('nm_envolvido', 'ilike', $name)->where('mae','ilike', $mother)->first();

        return $sisp;
    }
    public static function getBopsEnvByNameAndBirthday($name, $birth){
        return BopEnv::query()->where('nm_envolvido', 'ilike',$name)->whereRaw('DATE(bopenv.nascimento) = ?',$birth)->first();
    }

}
