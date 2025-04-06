<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserStatusUpdatingRequest;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SystemMemberAccountStatusChanger;

class UserAccountStatusChanger extends SystemMemberAccountStatusChanger
{
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UserStatusUpdatingRequest::class);
    }
 
}
