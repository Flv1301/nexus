<?php

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViewSearchPriosoner extends Model
{
    protected $connection = "seap";
    protected $table = "view_procura_preso";


    public function __construct()
    {

    }


    public static function getNameFields(){
     return DB::connection("pgsql2")->select("SELECT    attname
FROM pg_class c
INNER JOIN pg_attribute a ON attrelid = oid
INNER JOIN pg_type t ON oid = atttypid
WHERE relname = 'view_procura_preso' and attname <> 'xmax' and attname <> 'cmax' and attname <> 'cmin' and attname <> 'tableoid' and attname <> 'xmin' and attname <> 'ctid'");

    }

    public static function getFieldsNames()
    {
        $fields = ViewSearchPriosoner::getNameFields();
        $options = '';
        foreach ($fields as $field){
            $options .= '<option value="'.$field->attname.'">'.$field->attname.'</option>'   ;
        }
        echo html_entity_decode($options);
    }

    public static function getPrisoner($array){
        $presos = ViewSearchPriosoner::query()->distinct("matricula")->where($array)->get();
        //$presos = DB::connection("pgsql2")->query()->where($array);
        return $presos;
}

}
