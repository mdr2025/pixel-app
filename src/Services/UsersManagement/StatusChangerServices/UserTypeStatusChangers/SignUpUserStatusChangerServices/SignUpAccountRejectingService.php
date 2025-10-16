<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use PixelApp\Notifications\UserNotifications\StatusNotifications\RejectedRegistrationNotification;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices\SignUpAccountRejectingService as BaseSignUpAccountRejectingService;
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;

class SignUpAccountRejectingService extends BaseSignUpAccountRejectingService
{
    
    use EditableUserCheckingMethods;
    
    protected function getNotificationClass() : ?string
    {
        return $this->model->status == "rejected" 
               ? RejectedRegistrationNotification::class
               : null;
    }
}
