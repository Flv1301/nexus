<?php

namespace App\Models\Galton;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Galton extends Model
{
    /**
     * @var string
     */
    protected $connection = 'galton';
    /**
     * @var string
     */
    protected $table = 'prontuario';
    /**
     * @var string
     */
    protected $primaryKey = 'prontuario';

    public static function getFieldsNames()
    {
        $fields = Galton::getNameFields();
        $options = '';
        foreach ($fields as $field) {
            $options .= '<option value="' . $field->attname . '">' . $field->attname . '</option>';
        }
        echo html_entity_decode($options);
    }

    public static function getNameFields()
    {
        return DB::connection("pgsql2")->select(
            "SELECT    attname
FROM pg_class c
INNER JOIN pg_attribute a ON attrelid = oid
INNER JOIN pg_type t ON oid = atttypid
WHERE relname = 'prontuario' and attname <> 'xmax' and attname <> 'cmax' and attname <> 'cmin' and attname <> 'tableoid' and attname <> 'xmin' and attname <> 'ctid'"
        );
    }

    public static function getGaltonbyProntuario($prontuario)
    {
        return Galton::query()->where('prontuario', '=', $prontuario)->first();
    }

    public static function getGaltonbyCPF($cpf)
    {
        return Galton::query()->where('cpf', 'ilike', $cpf)->first();
    }

    public static function getGaltonByMotherAndName($name, $mother)
    {
        return Galton::query()->where('nome', 'ilike', $name)->where('mae', 'ilike', $mother)->first();
    }

    public static function getGaltonByNameAndBirth($name, $birth)
    {
        return Galton::query()->where('nome', 'ilike', $name)->whereRaw('DATE(data_nascimento) = ?', $birth)->first();
    }

    public static function getGaltons($array)
    {
        return Galton::query()->where($array)->get();
    }


}
