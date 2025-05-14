<?php

namespace App\Providers;

use App\Events\FacebookEvent;
use App\Events\LogUserAccessEvent;
use App\Events\WhatsappEvent;
use App\Listeners\FacebookListener;
use App\Listeners\LogUserAccessListener;
use App\Listeners\WhatsappListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        WhatsappEvent::class => [WhatsappListener::class],
        FacebookEvent::class => [FacebookListener::class],
        LogUserAccessEvent::class => [LogUserAccessListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
