<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 17/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Imei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    use HasFactory;

    protected $table = 'imeis';

    protected $fillable = ['imei', 'device'];
}
