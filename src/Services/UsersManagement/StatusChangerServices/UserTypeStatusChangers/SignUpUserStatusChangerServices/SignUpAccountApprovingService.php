<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserApprovingRequest; 
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger; 

class SignUpAccountApprovingService extends SignUpAccountStatusChanger
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
        return SignupUserApprovingRequest::class;
    }
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( static::getApprovingRequestFormBaseClass() );
    }

    public static function mustCheckRoleId() : void
    {
        static::$signUpApprovmentPropChangers[UserRoleChanger::class] = UserRoleChanger::class ;
        static::getApprovingRequestFormBaseClass()::mustCheckRoleId();
    }

    
    public static function mustCheckDepartmentId() : void
    {
        static::$signUpApprovmentPropChangers[ DepartmentChanger::class] = DepartmentChanger::class ;
        static::getApprovingRequestFormBaseClass()::mustCheckDepartmentId();
    }
   
    public static function mustCheckBranchId() : void
    {
        static::$signUpApprovmentPropChangers[BranchChanger::class] = BranchChanger::class ;
        static::getApprovingRequestFormBaseClass()::mustCheckBranchId();
    }

    protected function getSignUpApprovmentPropChangers() : array
    {
        return static::$signUpApprovmentPropChangers;
    }  
    
    protected function initUserSensitiveDataChanger() : UserSensitiveDataChanger
    {
        return new UserSensitiveDataChanger($this->user , $this->data);
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function setUserRelationships(): self
    { 
        /** Will change user's props by reference */
        $this->initUserSensitiveDataChanger()
             ->changeProps($this->getSignUpApprovmentPropChangers());
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function changeUserStatus(): self
    {
        parent::changeUserStatus();
        return $this->setUserRelationships();
    }
}
