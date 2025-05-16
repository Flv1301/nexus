<?php

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
