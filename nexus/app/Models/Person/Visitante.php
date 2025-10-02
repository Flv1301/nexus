<?php

namespace App\Models\Person;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'nome',
        'cpf',
        'tipo_vinculo'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
} 