<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Files\Facebook;

use App\Models\Utils\IpInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class FacebookAcessLogIpAddress extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'facebook_accesslog_ip_address';

    /**
     * @var string[]
     */
    protected $fillable = [
        'time',
        'ip_address'
    ];

    /**
     * @return Attribute
     */
    protected function time(): Attribute
    {
        return Attribute::get(
            fn($value) => Carbon::createFromTimeString($value)->format('d/m/Y H:i')
        );
    }

    /**
     * @return BelongsTo
     */
    public function facebook(): BelongsTo
    {
        return $this->belongsTo(Facebook::class, 'id');
    }

    /**
     * @return HasOne
     */
    public function ipinfo(): HasOne
    {
        return $this->hasOne(IpInfo::class, 'ip', 'ip_address');
    }

}
