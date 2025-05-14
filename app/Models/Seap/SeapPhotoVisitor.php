<?php

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

class SeapPhotoVisitor extends Model
{
    /** @var string */
    protected $connection = "seap";
    /** @var string */
    protected $table = "visitante_foto";

    public static function getPhotoById($id){
        return SeapPhotoVisitor::query()->where('id_visitante','=',$id)->first();
    }

}
