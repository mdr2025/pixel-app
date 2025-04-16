<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\ResetPasswordRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PasswordReset;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;

class PasswordResettingService
{
    use GeneralValidationMethods;

    protected PasswordReset $passwordReset;

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ResetPasswordRequest::class);
    }

    protected function initPasswordPropChanger() : PasswordChanger
    {
        return (new PasswordChanger())->setData( $this->data );
    }

    /**
     * @throws Exception
     */
    protected function getNewPasswordData() :  array
    {
        return  $this->initPasswordPropChanger()
                     ->setPropRequestKeyName("new_password")
                     ->getPropChangesArray();
    }

    protected function deletePasswordResetModel() : self
    {
        $this->passwordReset->delete();
        return $this;
    }

    protected function handleTenancySyncingData(PixelUser $user)
    { 
        PixelTenancyManager::handleTenancySyncingData($user);
        //event(new TenantModelDataSyncNeedEvent($this->user)); 
    }
    
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function updateUserPassword(PixelUser $user) : bool
    {
        return $user?->update( $this->getNewPasswordData() ) ?? false;
    }

    protected function fetchUser() : ?PixelUser
    {
        return $this->getUserModelClass()::where("email", $this->passwordReset->email)->first();
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function resetUserPassword(): self
    {
        $user = $this->fetchUser();
        
        if(! $this->updateUserPassword($user) )
        {
            throw new Exception("Failed To Reset Password");
        }

        $this->handleTenancySyncingData($user);

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function setPasswordResetModel(): self
    {
        $resetPasswordModel = PasswordReset::withResetPasswordToken($this->data["token"])->latest()->first();
        if (!$resetPasswordModel)
        {
            throw new Exception("Invalid Reset Password Token !");
        }
        $this->passwordReset = $resetPasswordModel;
        return $this;
    }

    public function reset(): JsonResponse
    {
        try {
            /** Validation operations */
            $this->initValidator()->validateRequest()->setRequestData();

            /** Password processing operations */
            $this->setPasswordResetModel()->resetUserPassword()->deletePasswordResetModel();

            return Response::success([], ["Password Has Been Reset Successfully"], 200);

        } catch (Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }
}
