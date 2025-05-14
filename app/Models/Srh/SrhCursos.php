<?php

namespace App\Models\Srh;

use Illuminate\Database\Eloquent\Model;

class SrhCursos extends Model
{
    protected $connection = "srh";

    protected $table = "srh_cursos_servidor";

    public static function getCourseByMat($mat)
    {
        return SrhCursos::query()->where('matricula', '=', $mat)->orderBy('ano_conclusao')->get();
    }
}
