<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rais extends Model
{
    use HasFactory;

    protected $table = 'rais';

    protected $fillable = [
        'person_id',
        'empresa_orgao',
        'cnpj',
        'tipo_vinculo',
        'admissao',
        'situacao',
    ];

    protected $casts = [
        'admissao' => DateCast::class,
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 