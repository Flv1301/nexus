<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pcpa extends Model
{
    use HasFactory;

    protected $table = 'pcpas';

    protected $fillable = [
        'person_id',
        'bo',
        'natureza',
        'data',
        'uf',
    ];

    protected $casts = [
        'data' => DateCast::class,
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 