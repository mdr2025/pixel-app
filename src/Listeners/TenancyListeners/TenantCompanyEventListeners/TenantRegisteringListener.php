<?php

namespace PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners;

 use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices\DefaultAdminVerificationNotificationResendingService;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationNotificationSenders\EmailAuthenticatableVerificationNotificationSender;

class TenantRegisteringListener implements ShouldQueue
{

    /**
     * @throws Exception
     */
    protected function initDefaultAdminVerificationNotificationSender($event): EmailAuthenticatableVerificationNotificationSender
    {
        return DefaultAdminVerificationNotificationResendingService::initVerificationNotificationSender($event->tenant->defaultAdmin);
    }

    /**
     * @throws Exception
     */
    protected function sendDefaultAdminVerificationNotificationSender($event): void
    {
        $this->initDefaultAdminVerificationNotificationSender($event)->sendEmailVerificationNotification();
    }

    protected function generateCompanyIdString($event): self
    {
        $event->tenant->generateCompanyIdString();
        $event->tenant->save();
        return $this;
    }

    /**
     * Handle the event.
     *
     * @param Object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $this->generateCompanyIdString($event)->sendDefaultAdminVerificationNotificationSender($event);
    }
}
