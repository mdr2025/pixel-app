<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices;

use PixelApp\Events\TenancyEvents\CentralModelDataSyncNeedEvent;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
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
        return CompanyDefaultAdmin::class;
    }

    protected function verifyAuthenticatable(): self
    {
        parent::verifyAuthenticatable();
        if ($this->EmailAuthenticatable->tenant()->isApproved()) {
            event(new CentralModelDataSyncNeedEvent($this->EmailAuthenticatable));
        }
        return $this;
    }
}
