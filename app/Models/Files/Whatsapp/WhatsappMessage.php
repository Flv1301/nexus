<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 17/12/2022
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

class WhatsappMessage extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_messages';

    /**
     * @var string[]
     */
    protected $fillable = [
        "whatsapp_id",
        "timestamp",
        "message_id",
        "sender",
        "recipients",
        "group_id",
        "sender_ip",
        "sender_port",
        "sender_device",
        "type",
        "message_style",
        "message_size",
        "created_at",
        "updated_at"
    ];

    /**
     * @return Attribute
     */
    protected function timestamp(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value)->addHours(3)->format('d/m/Y H:i:s'));
    }

    /**
     * @return BelongsTo
     */
    public function ipInfo(): BelongsTo
    {
        return $this->belongsTo(IpInfo::class, 'sender_ip', 'ip');
    }
}
