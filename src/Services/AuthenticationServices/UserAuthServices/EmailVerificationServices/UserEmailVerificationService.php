<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices;

use PixelApp\Events\TenancyEvents\TenantModelDataSyncNeedEvent;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices\EmailVerificationService;

/**
 * @property PixelUser $EmailAuthenticatable
 */
class UserEmailVerificationService extends EmailVerificationService
{

    /**
     * @return string
     */
    protected function getAuthenticatableClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function verifyAuthenticatable(): EmailVerificationService
    {
        parent::verifyAuthenticatable();
        if($this->EmailAuthenticatable->canSyncData())
        {
            event(new TenantModelDataSyncNeedEvent($this->EmailAuthenticatable));
        }
        return $this;
    }
}
