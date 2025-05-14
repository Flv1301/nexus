<?php

namespace App\Models\Srh;

use Illuminate\Database\Eloquent\Model;

class SrhTempoServico extends Model
{
    protected $connection = "srh";
    protected $table = "srh_declaracao_tempo_servico";

    public static function getTimeJobById($id)
    {
        return SrhTempoServico::query()->where('fk_servidor', '=', $id)->get();
    }

}
