<?php

namespace App\Models\Srh;

use Illuminate\Database\Eloquent\Model;

class SrhAverbacao extends Model
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
        return SrhAverbacao::query()->where('fk_servidor', '=', $id)->get();
    }

}
