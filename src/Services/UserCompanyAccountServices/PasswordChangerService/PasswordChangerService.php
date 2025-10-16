<?php

namespace PixelApp\Services\UserCompanyAccountServices\PasswordChangerService;
  
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserAccountRequests\ChangePasswordRequest;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Interfaces\TenancyInterfaces\CanSyncData;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;
  
class PasswordChangerService extends CustomUpdatingService
{

    public function __construct(Model |EmailAuthenticatable $model)
    {
        if(! $model instanceof EmailAuthenticatable)
        {
            dd("The model wanted to change its password must implement EmailAuthenticatable interface");
        }

        parent::__construct($model);
    }

    protected function handleTenancySyncingData()
    {
        PixelTenancyManager::handleTenancySyncingData($this->model);
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ChangePasswordRequest::class);
    }

    protected function initPasswordPropChanger() : PasswordChanger
    {
        return (new PasswordChanger())->setData($this->data)->setAuthenticatable($this->model);
    }

    protected function getUpdatingData() : array
    {
        return $this->initPasswordPropChanger()
                    ->mustCheckOldPassword()
                    ->setPropRequestKeyName("new_password")
                    ->getPropChangesArray();
    }

    protected function updateModel() : bool
    {
        $newData = $this->getUpdatingData();
        return $this->model->update( $newData );
    }
    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        if($this->updateModel() )
        {
            $this->handleTenancySyncingData();
        }
        return Response::success([], ["Updated Successfully"], 201);
    }
    
}
