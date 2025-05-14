<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 23/03/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models;

use App\Models\VCard\VCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UploadFile extends Model
{
    use HasFactory;
    use LogsActivity;
    //use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'uploadfiles';

    /**
     * @var string[]
     */
    protected $fillable = [
        'original_name',
        'filename',
        'mime_type',
        'extension',
        'hash_name',
        'user_id'
    ];

    /**
     * @return HasMany
     */
    public function vcards(): HasMany
    {
        return $this->hasMany(VCard::class, 'uploadfile_id', 'id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Upload de Arquivo');
    }
}
