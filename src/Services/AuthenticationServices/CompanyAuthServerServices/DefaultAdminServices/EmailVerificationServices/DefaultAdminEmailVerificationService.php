<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\CentralModelDataSyncNeedEvent;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices\EmailVerificationService;

/**
 * @property CompanyDefaultAdmin $EmailAuthenticatable
 */
class DefaultAdminEmailVerificationService extends EmailVerificationService
{ 
    /**
     * @return string
     */
    protected function getAuthenticatableClass(): string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
    }

    protected function verifyAuthenticatable(): self
    {
        parent::verifyAuthenticatable();
        
        PixelTenancyManager::handleTenancySyncingData($this->EmailAuthenticatable);
           
        return $this;
    }
}
