<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Events;

use App\Models\Files\Facebook\Facebook;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FacebookEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Facebook
     */
    public Facebook $facebook;

    /**
     * @param Facebook $facebook
     */
    public function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }
}
