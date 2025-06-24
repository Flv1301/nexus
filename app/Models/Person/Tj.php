<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tj extends Model
{
    use HasFactory;

    protected $table = 'tjs';

    protected $fillable = [
        'person_id',
        'situacao',
        'data_denuncia',
        'data_condenacao',
        'processo',
        'natureza',
        'data',
        'uf',
        'comarca',
    ];

    protected $casts = [
        'data' => DateCast::class,
        'data_denuncia' => DateCast::class,
        'data_condenacao' => DateCast::class,
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 