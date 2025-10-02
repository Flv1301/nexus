<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Listeners;

use App\Events\FacebookEvent;
use App\Services\Socialmedias\FacebookIpService;
use Illuminate\Support\Facades\Log;

class FacebookListener
{
    /**
     * @param FacebookEvent $event
     * @return void
     */
    public function handle(FacebookEvent $event): void
    {
        $facebookService = new FacebookIpService();
        $facebookService->setIps($event->facebook);
        $facebookService->store();
    }

    /**
     * @param FacebookEvent $event
     * @param $exception
     * @return void
     */
    public function failed(FacebookEvent $event, $exception): void
    {
        Log::error($exception->getMessage());
    }
}
