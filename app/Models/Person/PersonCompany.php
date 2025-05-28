<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonCompany extends Model
{
    use HasFactory;

    protected $table = 'person_companies';

    protected $fillable = [
        'person_id',
        'company_name',
        'fantasy_name',
        'cnpj',
        'phone',
        'social_capital',
        'status',
        'cep',
        'address',
        'number',
        'district',
        'city',
        'uf',
        'cnae',
        'accountant',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 