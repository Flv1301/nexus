<?php

namespace App\Models\VCard;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VCard extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'vcards';
    /**
     * @var string[]
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'prefix',
        'suffix',
        'fullname',
        'person_id',
        'uploadfile_id'
    ];

    /**
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(
            VCardPhone::class,
            'vcard_id',
            'id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(
            Person::class,
            'person_id',
            'id'
        );
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('VCard');
    }
}
