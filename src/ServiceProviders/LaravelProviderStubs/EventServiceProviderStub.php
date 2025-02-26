<?php

namespace App\Providers;

 use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use PixelApp\Events\EmailAuthenticatableEvents\EmailAuthenticatableRegisteredEvent;
use PixelApp\Events\EmailAuthenticatableEvents\EmailChangingEvent;
use PixelApp\Listeners\EmailAuthenticatableEventsListeners\EmailChangingMessageSenderListener;
use PixelApp\Listeners\EmailAuthenticatableEventsListeners\EmailVerificationSenderListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        EmailAuthenticatableRegisteredEvent::class => [
            EmailVerificationSenderListener::class
        ],
        EmailChangingEvent::class => [
            EmailVerificationSenderListener::class,
            EmailChangingMessageSenderListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

    }
}
