<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Image extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'images';

    protected $fillable = [
        'description',
        'path',
        'person_id',
        'cover'
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Image');
    }
}
