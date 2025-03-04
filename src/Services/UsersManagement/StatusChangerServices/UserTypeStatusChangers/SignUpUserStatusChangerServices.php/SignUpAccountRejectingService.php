<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Illuminate\Http\JsonResponse;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserRejectingRequest; 

class SignUpAccountRejectingService extends SignUpAccountStatusChanger
{
 
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(SignupUserRejectingRequest::class);
    }
 
    //it is just an alias method
    public function reject() : JsonResponse
    {
        return $this->change();
    }
}
