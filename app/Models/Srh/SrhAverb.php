<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Srh;

use Illuminate\Database\Eloquent\Model;

class SrhAverb extends Model
{
    /**
     * @var string
     */
    protected $connection = "srh";
    /**
     * @var string
     */
    protected $table = "srh_averbacao_servidor";

    public static function getAverbById($id)
    {
        return SrhAverb::query()->where('fk_servidor', '=', $id)->get();
    }
}
