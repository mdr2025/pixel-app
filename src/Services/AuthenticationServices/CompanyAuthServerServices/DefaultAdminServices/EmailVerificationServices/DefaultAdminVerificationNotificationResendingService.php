<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices\VerificationNotificationResendingService;

/**
 * @property CompanyDefaultAdmin $EmailAuthenticatable
 */
class DefaultAdminVerificationNotificationResendingService extends VerificationNotificationResendingService
{

    protected static function getVerificationFrontendURI(): string
    {
        return "company-account-verification";
    }

    protected function getAuthenticatableClass(): string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
    }

    protected function sendEmailVerificationNotification() : bool
    {
        $sendingResult = parent::sendEmailVerificationNotification();

        
        PixelTenancyManager::handleTenancySyncingData($this->EmailAuthenticatable); 
        
        return $sendingResult;
    }    
}
