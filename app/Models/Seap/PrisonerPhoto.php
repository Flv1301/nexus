<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrisonerPhoto extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = "seap";
    /**
     * @var string
     */
    protected $table = "preso_foto";
    /**
     * @var string[]
     */
    protected $fillable = [
        'presofoto_fotobin'
    ];
}
