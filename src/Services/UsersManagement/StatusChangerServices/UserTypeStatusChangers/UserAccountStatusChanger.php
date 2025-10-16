<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserStatusUpdatingRequest;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SystemMemberAccountStatusChanger;
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;
use PixelApp\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\InactiveRegistrationNotification;

class UserAccountStatusChanger extends SystemMemberAccountStatusChanger
{
    use EditableUserCheckingMethods;

    protected static $statusNotificationMap = [
        'active' => ActiveRegistrationNotification::class,
        'inactive' => InactiveRegistrationNotification::class,
    ];
    
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UserStatusUpdatingRequest::class);
    }
 
}
