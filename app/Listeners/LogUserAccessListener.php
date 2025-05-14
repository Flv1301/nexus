<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/06/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Listeners;

use App\Events\LogUserAccessEvent;
use App\Services\LogUserAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogUserAccessListener
{
    /**
     * @param LogUserAccessEvent $event
     * @return void
     */
    public function handle(LogUserAccessEvent $event): void
    {
        $logUserService = new LogUserAccessService();
        $logUserService->userAccessHistory($event->request);
    }

    /**
     * @param Request $event
     * @param $exception
     * @return void
     */
    public function failed(Request $event, $exception): void
    {
        Log::error($exception->getMessage());
    }
}
