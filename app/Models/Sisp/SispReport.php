<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of SispReport
 *
 * @author 55919
 */
class SispReport  extends Model{
    //put your code here
    protected $connection = "sisp";
    protected $table = "boprel";
    public static function getReport($array){
       return SispReport::query()->where($array)->limit(50)->get();
    }
}
