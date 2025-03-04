<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Exception;
use PixelApp\Services\UsersManagement\StatusChangerServices\AccountStatusChanger;

abstract class SignUpAccountStatusChanger extends AccountStatusChanger
{ 
    abstract protected function getRequestFormClass(): string;
 
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


}
