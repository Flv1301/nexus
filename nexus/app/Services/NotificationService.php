<?php

namespace App\Services;

use App\Models\Emails\EmailNotification;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * @param string $type
     * @param Notification $notification
     * @return void
     */
    public static function send(string $type, Notification $notification): void
    {
        $usersNotifications = EmailNotification::with('user')
            ->where('type', $type)
            ->where('status', true)
            ->get();

        if ($usersNotifications->isNotEmpty()) {
            $users = $usersNotifications->pluck('user');
            \Illuminate\Support\Facades\Notification::send($users, $notification);
        }
    }
}
