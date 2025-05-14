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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CaseType extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'case_types';

    /**
     * @var string[]
     */
    protected $fillable = [
        'description',
        'status',
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Tipo de Caso');
    }
}
