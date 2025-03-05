<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserRejectingRequest;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\UsersModule\PixelUser;

class SignUpAccountRejectingService extends SignUpAccountStatusChanger
{
    public function __construct(PixelUser | Authenticatable |EmailAuthenticatable $user)
    {
        parent::__construct($user);
        $this->mergeStatusValueToRequest("rejected");
    }

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
