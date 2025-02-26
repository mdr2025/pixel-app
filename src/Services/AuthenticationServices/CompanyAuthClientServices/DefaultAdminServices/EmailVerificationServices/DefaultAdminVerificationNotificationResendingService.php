<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\DefaultAdminServices\EmailVerificationServices;

use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices\VerificationNotificationResendingService;

class DefaultAdminVerificationNotificationResendingService extends VerificationNotificationResendingService
{

    protected static function getVerificationFrontendURI(): string
    {
        return "company-account-verification";
    }

    protected function getAuthenticatableClass(): string
    {
        return CompanyDefaultAdmin::class;
    }
}
