<?php

namespace App\Models\Cases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'case_types';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    /**
     * @return HasMany
     */
    public function cases(): HasMany
    {
        return $this->hasMany(Cases::class, 'type_id', 'id');
    }
} 