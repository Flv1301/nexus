<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Srh;


/**
 * Description of Bop
 *
 * @author 55919
 */
class SrhHistoricoLocalizacao extends \Illuminate\Database\Eloquent\Model
{
    //put your code here
    protected $connection = "srh";
    protected $table = "srh_hist_loc";

    public static function getHistByIdEmployee($id)
    {
        return SrhHistoricoLocalizacao::query()->where('fk_servidor', '=', $id)->orderBy('dat_lotacao')->get();
    }

}
