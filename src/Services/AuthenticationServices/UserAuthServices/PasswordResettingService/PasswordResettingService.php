<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\ResetPasswordRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PasswordReset;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;

class PasswordResettingService
{
    use GeneralValidationMethods;

    private PasswordReset $passwordReset;

    protected function getRequestFormClass(): string
    {
        return ResetPasswordRequest::class;
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

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
    /**
     * @return $this
     * @throws Exception
     */
    private function resetUserPassword(): self
    {
        if(! $this->getUserModelClass()::where("email", $this->passwordReset->email)->update( $this->getNewPasswordData() ) )
        {
            throw new Exception("Failed To Reset Password");
        }
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
