<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bnmp extends Model
{
    use HasFactory;

    protected $table = 'bnmps';

    protected $fillable = [
        'person_id',
        'numero_mandado',
        'orgao_expedidor',
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 