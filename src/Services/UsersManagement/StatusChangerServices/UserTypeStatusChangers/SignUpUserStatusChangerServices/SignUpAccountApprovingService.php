<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger; 

use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices\SignUpAccountApprovingService as BaseSignUpAccountApprovingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;

class SignUpAccountApprovingService extends BaseSignUpAccountApprovingService
{
    // protected static array $signUpApprovmentPropChangers = [];
  
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( static::getApprovingRequestFormBaseClass() );
    }

    // public static function mustCheckRoleId() : void
    // {
    //     static::$signUpApprovmentPropChangers[UserRoleChanger::class] = UserRoleChanger::class ;
    //     static::getApprovingRequestFormBaseClass()::mustCheckRoleId();
    // }
 
    // public static function mustCheckDepartmentId() : void
    // {
    //     static::$signUpApprovmentPropChangers[ DepartmentChanger::class] = DepartmentChanger::class ;
    //     static::getApprovingRequestFormBaseClass()::mustCheckDepartmentId();
    // }
   
    // public static function mustCheckBranchId() : void
    // {
    //     static::$signUpApprovmentPropChangers[BranchChanger::class] = BranchChanger::class ;
    //     static::getApprovingRequestFormBaseClass()::mustCheckBranchId();
    // }

    
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    protected function getSignUpApprovmentPropChangers() : array
    {
        $userModelClass = $this->getUserModelClass();
        return AdminAssignablePropsManager::Singleton()->getSensitivePropChangersForClass($userModelClass);
        // return static::$signUpApprovmentPropChangers;
    }  
    
    protected function initUserSensitiveDataChanger() : UserSensitiveDataChanger
    {
        return new UserSensitiveDataChanger($this->model , $this->data);
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
    protected function changeAuthenticatableStatus(): self
    {
        parent::changeAuthenticatableStatus();
        return $this->setUserRelationships();
    }
}
