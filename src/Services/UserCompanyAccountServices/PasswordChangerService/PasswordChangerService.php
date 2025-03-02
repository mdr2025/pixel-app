<?php

namespace PixelApp\Services\UserCompanyAccountServices\PasswordChangerService;
  
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserAccountRequests\ChangePasswordRequest;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;

class PasswordChangerService extends CustomUpdatingService
{
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ChangePasswordRequest::class);
    }

    protected function initPasswordPropChanger() : PasswordChanger
    {
        return (new PasswordChanger())->setData($this->data)->setAuthenticatable($this->user);
    }
    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        $newData = $this->initPasswordPropChanger()
                                ->mustCheckOldPassword()
                                ->setPropRequestKeyName("new_password")
                                ->getPropChangesArray();

        $this->user->update( $newData );
        return Response::success([], ["Updated Successfully"], 201);
    }
}
