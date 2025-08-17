<?php

namespace PixelApp\Listeners\EmailAuthenticatableEventsListeners;


use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserVerificationNotificationResendingService;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationNotificationSenders\EmailAuthenticatableVerificationNotificationSender;

class NewEmailVerificationSenderListener implements ShouldQueue
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

    /**
     * @throws Exception
     */
    protected function initEmailAuthenticatableVerificationNotificationSender($event) : EmailAuthenticatableVerificationNotificationSender
    {
        return UserVerificationNotificationResendingService::initVerificationNotificationSender(
                                                                $event->getAuthenticatable()
                                                            );
    }
    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $this->initEmailAuthenticatableVerificationNotificationSender($event)
             ->resetVerificationToken()
             ->sendEmailVerificationNotification();
    }
}
