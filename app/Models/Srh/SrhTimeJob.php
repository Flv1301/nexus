<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Srh;

/**
 * Description of SrhTimeJob
 *
 * @author 55919
 */
class SrhTimeJob extends \Illuminate\Database\Eloquent\Model
{
    //put your code here
    protected $connection = "srh";
    protected $table = "srh_declaracao_tempo_servico";

    public static function getTimeJobById($id)
    {
        return SrhTimeJob::query()->where('fk_servidor', '=', $id)->get();
    }

}
