<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Models\Files\Whatsapp;

use App\Models\Utils\IpInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class WhatsappCallEvent extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_call_events';

    /**
     * @var string[]
     */
    protected $fillable = [
        "whatsapp_call_id",
        "type",
        "timestamp",
        "from",
        "to",
        "from_ip",
        "from_port",
        "media_type"
    ];

    protected Collection $ips;

    /**
     * @return Attribute
     */
    protected function timestamp(): Attribute
    {
        return Attribute::get(
            fn($value) => Carbon::parse($value)->addHours(3)->format('d/m/Y H:i:s')
        );
    }


    /**
     * @return BelongsTo
     */
    public function ipInfo(): BelongsTo
    {
        return $this->belongsTo(IpInfo::class, 'from_ip', 'ip');
    }
}
