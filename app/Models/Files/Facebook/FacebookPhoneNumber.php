<?php

namespace App\Models\Files\Facebook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'phone_number_verified',
        'facbook_id'
    ];
}
