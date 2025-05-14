<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

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
