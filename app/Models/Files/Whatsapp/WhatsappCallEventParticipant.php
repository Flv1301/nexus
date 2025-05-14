<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Models\Files\Whatsapp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappCallEventParticipant extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_call_event_participants';

    /**
     * @var string[]
     */
    protected $fillable = [
        "whatsapp_call_event_id",
        "phone_number",
        "state",
        "platform"
    ];
}
