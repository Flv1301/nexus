<?php

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SEAP extends Model
{
    use HasFactory;
    protected $connection = "seap";
    protected $table = "preso";



    public static function getPrisonerById($id){
      $preso = SEAP::query()->where('id_preso', '=', $id)->first();
      return $preso;
    }
    public static function getPrisonerByCPF($cpf){
      $preso = SEAP::query()->join('seap.preso_documento','preso.id_preso','=','preso_documento.id_preso')->where('presodocumento_numero','=',$cpf)->first();
      return $preso;
    }
    public static function getPrisonerByMotherAndName($name, $mother){
      $preso = SEAP::query()->where('preso_nome','ilike',$name)->where('presofiliacao_mae','ilike',$mother)->first();
      return $preso;
    }
    public static function getPrisonerByNameAndBirthday($name, $birth){
      return SEAP::query()->where('preso_nome', 'ilike',$name)->whereRaw('DATE(preso_datanascimento) = ?',$birth)->first();
    }





}

