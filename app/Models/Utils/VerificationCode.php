<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'expires_at',
        'registration',
        'status',
        'created_at',
        'updated_at'
    ];
}
