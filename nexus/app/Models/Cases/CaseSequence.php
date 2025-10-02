<?php

namespace App\Models\Cases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSequence extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'case_sequences';

    /**
     * @var string[]
     */
    protected $fillable = [
        'sequence',
        'year',
        'created_at',
        'updated_at'
    ];
}
