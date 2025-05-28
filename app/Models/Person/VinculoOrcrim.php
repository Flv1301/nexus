<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VinculoOrcrim extends Model
{
    use HasFactory;

    protected $table = 'vinculo_orcrims';

    protected $fillable = [
        'person_id',
        'name',
        'alcunha',
        'cpf',
        'tipo_vinculo',
        'orcrim',
        'cargo',
        'area_atuacao',
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 