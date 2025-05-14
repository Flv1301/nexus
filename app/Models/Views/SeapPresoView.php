<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Views;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeapPresoView extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = "seap";
    /**
     * @var string
     */
    protected $table = "seap_preso_view";
    /**
     * @var string
     */
    protected $primaryKey = 'id_preso';

    /**
     * @var string[]
     */
    protected $casts = [
        'preso_dataultimaprisao' => DateCast::class,
        'preso_datanascimento' => DateCast::class,
    ];
}
