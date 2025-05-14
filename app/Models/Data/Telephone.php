<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Data;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Telephone extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'telephones';
    /**
     * @var string[]
     */
    protected $fillable = [
        'ddd',
        'telephone',
        'operator',
        'owner',
        'start_link',
        'end_link',
        'status',
        'user_id',
        'imei',
        'imsi',
        'device',
    ];

    /**
     * @return Attribute
     */
    protected function telephone(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::of($v)->replaceMatches('/[^0-9]++/', '')
        );
    }

    /**
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'person_telephones',
            'telephone_id',
            'person_id'
        );
    }

    /**
     * @return Attribute
     */
    protected function device(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::upper($v)
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
            ->useLogName('Telephone');
    }
}
