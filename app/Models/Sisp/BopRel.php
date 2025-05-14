<?php

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;

/**
 * Description of BopRel
 *
 * @author 55919
 */
class BopRel extends Model
{
    //put your code here
    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "boprel";

    /**
     * @param $key
     * @return HigherOrderBuilderProxy|mixed
     */
    public static function getBopByKey($key)
    {
        $relato = BopRel::query()->where('boprel_bop_key', '=', $key)->first();
        return $relato->relato;
    }

}
