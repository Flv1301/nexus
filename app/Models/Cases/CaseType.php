<?php

namespace App\Models\Cases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
} 