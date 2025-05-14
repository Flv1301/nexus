<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Events;

use App\Models\Files\Whatsapp\Whatsapp;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsappEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Whatsapp
     */
    public Whatsapp $whatsapp;

    /**
     * @param Whatsapp $whatsapp
     */
    public function __construct(Whatsapp $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }
}
