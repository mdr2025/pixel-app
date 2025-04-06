<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices;
 
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse; 
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupAccountApprovingRequest; 
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\UsersModule\PixelUser; 

abstract class SignUpAccountApprovingService extends SignUpAccountStatusChanger
{
    protected static array $signUpApprovmentPropChangers = [];

    public function __construct(PixelUser | Authenticatable |EmailAuthenticatable $user)
    {
        parent::__construct($user);
        $this->mergeStatusValueToRequest("active");
    }

    //it is just an alias method
    public function approve() : JsonResponse
    {
        return $this->change();
    }


    protected static function getApprovingRequestFormBaseClass() :  string
    {
        return SignupAccountApprovingRequest::class;
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( static::getApprovingRequestFormBaseClass() );
    }
   
}
