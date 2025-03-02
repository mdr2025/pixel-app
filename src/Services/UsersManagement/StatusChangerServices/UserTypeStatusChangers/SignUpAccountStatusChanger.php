<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserStatusUpdatingRequest;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;
use PixelApp\Services\UsersManagement\StatusChangerServices\AccountStatusChanger;

class SignUpAccountStatusChanger extends AccountStatusChanger
{
    protected static ?array $signUpApprovmentPropChangers = null;

    public static function setSignUpApprovmentPropChangers(array $changers) : void
    {
        $classes = array_filter($changers , function($class)
                    {
                        return is_subclass_of($class , UserSensitivePropChanger::class);
                    });
        static::$signUpApprovmentPropChangers = $classes;
    }
    protected function getSignUpApprovmentPropChangers() : array
    {
        return static::$signUpApprovmentPropChangers ?? 
               [UserRoleChanger::class  , DepartmentChanger::class];
    }
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(SignupUserStatusUpdatingRequest::class);
    }

    protected function initUserSensitiveDataChanger() : UserSensitiveDataChanger
    {
        return new UserSensitiveDataChanger($this->user , $this->data);
    }

    /**
     * @return $this
     * @throws Exception
     * Protecting condition - For avoiding to call api to change status to a value used for another user type
     */
    protected function checkConditionsBeforeStart()  : self
    {
        if($this->user->user_type != "signup")
        {
            throw new Exception("Can't change user status");
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function setUserRelationships(): self
    {
        parent::setUserRelationships();

        /** Will change user's props by reference */
        $this->initUserSensitiveDataChanger()
             ->changeProps($this->getSignUpApprovmentPropChangers());
        return $this;
    }

}
