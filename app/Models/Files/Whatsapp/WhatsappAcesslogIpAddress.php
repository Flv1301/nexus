<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/02/2023
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Models\Files\Whatsapp;

use App\Models\Utils\IpInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class WhatsappAcesslogIpAddress extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_accesslog_ip_address';

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
            fn($value) => Carbon::parse($value)->addHours(3)->format('d/m/Y H:i:s')
        );
    }

    /**
     * @return BelongsTo
     */
    public function whatsapp(): BelongsTo
    {
        return $this->belongsTo(Whatsapp::class, 'id');
    }

    /**
     * @return BelongsTo
     */
    public function ipInfo(): BelongsTo
    {
        return $this->belongsTo(IpInfo::class, 'ip_address', 'ip');
    }
}
