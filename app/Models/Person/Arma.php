<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arma extends Model
{
    use HasFactory;

    protected $table = 'armas';

    protected $fillable = [
        'person_id',
        'cac',
        'marca',
        'modelo',
        'calibre',
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 