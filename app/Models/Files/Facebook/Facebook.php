<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 17/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Models\Files\Facebook;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Facebook extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'facebook';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string[]
     */
    protected $fillable = [
        "name",
        "extension",
        "view",
        "service",
        "account_identifier",
        "account_type",
        "generated",
        "date_range",
        "target",
        "registered_email_addresses",
        "vanity_name",
        "registration_date",
        "registration_ip",
        "phone_numbers",
        "phone_numbers_verified",
        "first_name",
        "last_name",
        "created_at",
        "updated_at"
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'date_range' => DateCast::class,
        'registration_date' => DateCast::class,
        'phone_numbers_verified' => DateCast::class,
    ];

    /**
     * @param $value
     * @return string
     */
    public function formatDateTime($value): string
    {
        return Carbon::createFromTimeString($value)->format('d/m/Y H:i');
    }


    /**
     * @return Attribute
     */
    protected function dateRange(): Attribute
    {
        return Attribute::get(function ($value) {
            if ($value) {
                $date_start = $this->formatDateTime(Str::before($value, ' to'));
                $date_end = $this->formatDateTime(Str::after($value, 'to '));
                return "{$date_start} ate {$date_end}";
            }
            return $value;
        });
    }

    /**
     * @return HasMany
     */
    public function acesslogIpAddress(): HasMany
    {
        return $this->hasMany(
            FacebookAcessLogIpAddress::class,
            'facebook_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(FacebookPhoneNumber::class, 'facebook_id', 'id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Arquivo de Facebook');
    }
}
