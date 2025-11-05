<?php
 
namespace  PixelApp\Services\UsersManagement\EmailChangerService\UserTypesEmailChangerServices;

use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserChangeEmailRequest;
use PixelApp\Models\UsersModule\PixelUser;

/**
 * @property PixelUser $model
 */
class SignupUserEmailChangerService extends EmailChangerService
{ 
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( SignupUserChangeEmailRequest::class);
    }
  
}
