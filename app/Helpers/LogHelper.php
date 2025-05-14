<?php

namespace App\Helpers;

class LogHelper
{
    /**
     * @param string $event
     * @param array|null $properties
     * @param string $logName
     * @param string $description
     * @return void
     */
    public static function logActivity(string $event, string $logName = '', string $description = '', mixed $properties = null): void
    {
        activity()
            ->event($event)
            ->withProperties($properties)
            ->useLog($logName)
            ->log($description);
    }
}
