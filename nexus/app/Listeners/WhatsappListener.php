<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Listeners;

use App\Events\WhatsappEvent;
use App\Services\Socialmedias\WhatsappIpService;
use Illuminate\Support\Facades\Log;

class WhatsappListener
{
    /**
     * @param WhatsappEvent $event
     * @return void
     */
    public function handle(WhatsappEvent $event): void
    {
        $whatsService = new WhatsappIpService($event->whatsapp);
        $whatsService->findIpAndStore();
    }

    /**
     * @param WhatsappEvent $event
     * @param $exception
     * @return void
     */
    public function failed(WhatsappEvent $event, $exception): void
    {
        Log::error($exception->getMessage());
    }
}
