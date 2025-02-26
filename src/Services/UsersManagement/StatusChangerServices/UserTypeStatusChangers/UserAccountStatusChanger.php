<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers;

use Exception;
use PixelApp\Http\Requests\UserManagementRequests\UserStatusUpdatingRequest;
use PixelApp\Services\UsersManagement\StatusChangerServices\AccountStatusChanger;

class UserAccountStatusChanger extends AccountStatusChanger
{
    protected function getRequestFormClass(): string
    {
        return UserStatusUpdatingRequest::class;
    }

    /**
     * @return $this
     * @throws Exception
     * Protecting condition - For avoiding to call api to change status to a value used for another user type
     */
    protected function checkConditionsBeforeStart()  : self
    {
        if($this->user->user_type != "user")
        {
            throw new Exception("Can't change user status");
        }
        return $this;
    }
}
