<?php

namespace  PixelApp\Services\UsersManagement\UpdatingUserByAdminService;

use PixelApp\Exceptions\JsonException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserUpdatingRequest;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;

class UpdatingUserByAdminService extends CustomUpdatingService
{
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UserUpdatingRequest::class);
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
             ->changeProps([
                                UserRoleChanger::class   , DepartmentChanger::class 
                                
                                /**
                                 * @todo later
                                 **/
                                //,  BranchChanger::class
                           ])->saveChanges();

        /** if no exception is thrown = every thing is ok and function get successful */
        return Response::success([], ["User Updated Successfully!"], 201);
    }
}
