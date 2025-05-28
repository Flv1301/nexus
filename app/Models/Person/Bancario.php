<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bancario extends Model
{
    use HasFactory;

    protected $table = 'bancarios';

    protected $fillable = [
        'person_id',
        'banco',
        'conta',
        'agencia',
        'data_criacao',
        'data_exclusao',
    ];

    protected $casts = [
        'data_criacao' => DateCast::class,
        'data_exclusao' => DateCast::class,
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 