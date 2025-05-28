<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doc extends Model
{
    use HasFactory;

    protected $table = 'docs';

    protected $fillable = [
        'person_id',
        'nome_doc',
        'data',
        'upload',
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