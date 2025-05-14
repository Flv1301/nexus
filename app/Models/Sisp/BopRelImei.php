<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BopRelImei extends Model
{
    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "boprel_imei";

    /**
     * @return HasOne
     */
    public function bop(): HasOne
    {
        return $this->hasOne(Bop::class, 'bop_bop_key', 'boprel_imei_bop_key');
    }

}
