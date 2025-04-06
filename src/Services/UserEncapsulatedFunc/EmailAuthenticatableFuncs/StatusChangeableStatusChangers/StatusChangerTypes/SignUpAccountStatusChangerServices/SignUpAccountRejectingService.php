<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupAccounRejectingRequest;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\UsersModule\PixelUser;

abstract class SignUpAccountRejectingService extends SignUpAccountStatusChanger
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
        $this->mergeStatusValueToRequest("rejected");
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(SignupAccounRejectingRequest::class);
    }
 
    //it is just an alias method
    public function reject() : JsonResponse
    {
        return $this->change();
    }
}
