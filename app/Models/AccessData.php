<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_local',
        'ip_global',
        'agent',
        'device',
        'type_device',
        'platform',
        'browser',
        'latitude',
        'longitude'
    ];
}
