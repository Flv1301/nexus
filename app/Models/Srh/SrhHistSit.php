<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Srh;

/**
 * Description of SrhHistSit
 *
 * @author 55919
 */
class SrhHistSit extends \Illuminate\Database\Eloquent\Model
{
    //put your code here
    protected $connection = "srh";
    protected $table = "srh_hist_sit";

    public static function getHistSitById($id)
    {
        return SrhHistSit::query()->where('fk_servidor', '=', $id)->get();
    }
}
