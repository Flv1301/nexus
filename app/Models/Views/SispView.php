<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Views;

use App\Casts\DateCast;
use App\Models\Sisp\BopEnv;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SispView extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "sisp_view";
    /**
     * @var string
     */
    protected $primaryKey = 'bop_bop_key';

    /**
     * @var string[]
     */
    protected $casts = [
        'dt_registro' => DateCast::class,
        'dt_fato' => DateCast::class
    ];

    protected $fillable = [
        'dt_registro',
        'n_bop',
        'unidade_responsavel',
        'sigiloso'
    ];

    /**
     * @return HasMany
     */
    public function bopenv(): HasMany
    {
        return $this->hasMany(BopEnv::class, 'bopenv_bop_key', 'bop_bop_key');
    }
}
