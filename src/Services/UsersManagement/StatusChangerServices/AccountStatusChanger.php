<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices;

use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\InactiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\RejectedRegistrationNotification;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\AccountStatusChanger as BaseAccountStatusChanger;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;

 /**
  * @property PixelUser $model
  */
abstract class AccountStatusChanger extends BaseAccountStatusChanger
{
  
    use EditableUserCheckingMethods;
    
    //Here We get the convenient Notification Class by mapping it to status value
    // that is more dynamic and with this solution there is no need to more if conditions
    protected static $statusNotificationMap = [
        'active' => ActiveRegistrationNotification::class,
        'inactive' => InactiveRegistrationNotification::class,
        'rejected' => RejectedRegistrationNotification::class,
    ];
 
 
    protected function checkPreConditions() : void
    {
        $this->checkDefaultAdmin($this->model);
    }
}
