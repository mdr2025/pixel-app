<?php

namespace PixelApp\Listeners\EmailAuthenticatableEventsListeners;

use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Notifications\UserNotifications\EmailNotifications\OldEmailChangingAttention;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailChangingMessageSenderListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected function setAuthenticatableOldEmail($event) : EmailAuthenticatable
    {
        /**@var EmailAuthenticatable $authenticatable  */
        $authenticatable = $event->getAuthenticatable();
        $authenticatable->{ $authenticatable->getEmailColumnName() } = $event->getOldEmail();
        return $authenticatable;
    }
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
       $this->setAuthenticatableOldEmail($event)->notify(new OldEmailChangingAttention());
    }
}
