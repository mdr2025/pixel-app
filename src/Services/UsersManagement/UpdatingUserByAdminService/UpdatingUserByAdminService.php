<?php

namespace  PixelApp\Services\UsersManagement\UpdatingUserByAdminService;

use PixelApp\Exceptions\JsonException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserUpdatingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger; 
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;

class UpdatingUserByAdminService extends CustomUpdatingService
{
    use EditableUserCheckingMethods; 
    
    protected function checkPreConditions() : void
    {
        $this->checkDefaultAdmin($this->model);
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UserUpdatingRequest::class);
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    protected function getUserUpdatingPropChangers() : array
    {
        $userModelClass = $this->getUserModelClass();
        return AdminAssignablePropsManager::Singleton()->getSensitivePropChangersForClass($userModelClass); 
    }  

    protected function initUserSensitiveDataChanger() : UserSensitiveDataChanger
    {
        return new UserSensitiveDataChanger($this->model , $this->data);
    }


    /**
     * @return JsonResponse
     * @throws JsonException
     * @throws Exception
     */
    protected function changerFun( ): JsonResponse
    {
        $this->initUserSensitiveDataChanger()
             ->changeProps( $this->getUserUpdatingPropChangers() )
             ->saveChanges();

        /** if no exception is thrown = every thing is ok and function get successful */
        return Response::success([], ["User Updated Successfully!"], 201);
    }
}
