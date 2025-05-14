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
use Illuminate\Support\Carbon;

class WhatsappCall extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_calls';

    /**
     * @var string[]
     */
    protected $fillable = [
        "call_id",
        "call_creator",
        "type",
        "timestamp",
        "from",
        "to",
        "from_ip",
        "from_port",
        "media_type",
        "whatsapp_id",
        "created_at",
        "updated_at"
    ];

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
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(WhatsappCallEvent::class, 'whatsapp_call_id');
    }
}
