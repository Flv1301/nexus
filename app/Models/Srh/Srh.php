<?php

namespace App\Models\Srh;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Srh extends Model
{
    /**
     * @var string
     */
    protected $connection = "srh";
    /**
     * @var string
     */
    protected $table = "srh";
    /**
     * @var string
     */
    protected $primaryKey = 'id_servidor';

    public function annotations(): HasMany
    {
        return $this->hasMany(SrhAverbacao::class, 'fk_servidor', 'id_servidor');
    }

    public function locationHistory(): HasMany
    {
        return $this->hasMany(SrhHistoricoLocalizacao::class, 'fk_servidor', 'id_servidor');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(SrhCursos::class, 'matricula', 'matricula');
    }

    /**
     * @return HasMany
     */
    public function serviceTime(): HasMany
    {
        return $this->hasMany(SrhTempoServico::class, 'fk_servidor', 'id_servidor');
    }


    public static function getEmployees($array)
    {
        return Srh::query()->where($array)->get();
    }

    public static function getFieldsNames()
    {
        $fields = Srh::getNameFields();
        $options = '';
        foreach ($fields as $field) {
            $options .= '<option value="' . $field->attname . '">' . $field->attname . '</option>';
        }
        echo html_entity_decode($options);
    }

    public static function getNameFields()
    {
        return DB::connection("srh")->select(
            "SELECT    attname
FROM pg_class c
INNER JOIN pg_attribute a ON attrelid = oid
INNER JOIN pg_type t ON oid = atttypid
WHERE relname = 'srh' and attname <> 'xmax' and attname <> 'cmax' and attname <> 'cmin' and attname <> 'tableoid' and attname <> 'xmin' and attname <> 'ctid'"
        );
    }


    public static function getEmployeeById($id)
    {
        return Srh::query()->where('id_servidor', '=', $id)->first();
    }

    public static function getEmployeeByCPF($cpf)
    {
        return Srh::query()->where('cpf', '=', $cpf)->first();
    }

    public static function getEmployeeByName($name)
    {
        return Srh::query()->where('nome_servidor', '=', strtoupper($name))->first();
    }

    public static function getEmployeeByNameAndBirth($name, $birth)
    {
        return Srh::query()->where('nome_servidor', 'ilike', $name)->whereRaw(
            'DATE(srh.nascimento) = ?',
            $birth
        )->first();
    }

}
