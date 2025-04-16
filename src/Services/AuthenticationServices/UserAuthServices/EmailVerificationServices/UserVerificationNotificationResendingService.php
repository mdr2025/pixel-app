<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices\VerificationNotificationResendingService;

/**
 * @property PixelUser $EmailAuthenticatable
 */
class UserVerificationNotificationResendingService extends VerificationNotificationResendingService
{

    protected function getAuthenticatableClass(): string
    {
        return PixelModelManager::getUserModelClass();
    }

    /**
     * @return string
     */
    protected static function getVerificationFrontendURI() : string
    {
        return "user-account-verification";
    }

    protected function fetchAuthenticatable(): Model|EmailAuthenticatable
    {
        /** @var PixelUser $authenticatable  */
        $authenticatable =  parent::fetchAuthenticatable();

        /** to avoid re-verifying by an admin ... it can be re-verified only when he changes his email while editing his profile */
        if(!$authenticatable->isEditableUser())
        {
            throw new Exception("Can't re-verify a default admin !");
        }
        return $authenticatable;
    }

    protected function sendEmailVerificationNotification() : bool
    {
        $sendingResult = parent::sendEmailVerificationNotification();

        PixelTenancyManager::handleTenancySyncingData($this->EmailAuthenticatable);
        //event(new TenantModelDataSyncNeedEvent($this->EmailAuthenticatable));
        
        return $sendingResult;
    }    
}
