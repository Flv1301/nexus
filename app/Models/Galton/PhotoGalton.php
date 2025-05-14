<?php

namespace App\Models\Galton;

use Illuminate\Database\Eloquent\Model;

class PhotoGalton extends Model
{
    protected $connection = 'galton';
    protected $table = 'foto';

    public static function getPhotoByGuia($guia)
    {


        return Galton::query()->where("guia", "=", $guia)->first();


    }

    public static function getAllPhotosByGuia($guia)
    {


        return Galton::query()->where("fk_pessoa", "=", $guia)->get();


    }

}
