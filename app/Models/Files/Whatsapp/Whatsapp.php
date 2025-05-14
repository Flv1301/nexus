<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 17/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Models\Files\Whatsapp;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Whatsapp extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'whatsapp';

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
        "case_id",
        "user_id",
        "created_at",
        "updated_at"
    ];

    /**
     * @param $value
     * @return string
     */
    public function formatDateTime($value): string
    {
        return Carbon::parse($value)->addHours(3)->format('d/m/Y H:i:s');
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
    public function calls(): HasMany
    {
        return $this->hasMany(WhatsappCall::class, 'whatsapp_id');
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class, 'whatsapp_id');
    }

    /**
     * @return HasMany
     */
    public function acesslogIpAddress(): HasMany
    {
        return $this->hasMany(WhatsappAcesslogIpAddress::class, 'whatsapp_id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Arquivo de Whatsapp');
    }
}
