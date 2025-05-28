<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'brand',
        'model',
        'year',
        'color',
        'plate',
        'jurisdiction',
        'status',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
} 