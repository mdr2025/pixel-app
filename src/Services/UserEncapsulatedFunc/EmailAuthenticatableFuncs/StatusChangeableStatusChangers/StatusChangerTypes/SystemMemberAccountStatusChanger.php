<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserStatusUpdatingRequest; 
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\AccountStatusChanger;


abstract class SystemMemberAccountStatusChanger extends AccountStatusChanger
{
    // protected function getRequestFormClass(): string
    // {
    //     return PixelHttpRequestManager::getRequestForRequestBaseType(UserStatusUpdatingRequest::class);
    // }

    /**
     * @return $this
     * @throws Exception
     * Protecting condition - For avoiding to call api to change status to a value used for another user type
     */
    protected function checkConditionsBeforeStart()  : self
    {
        if(! $this->model->isSystemMemberAccount())
        {
            throw new Exception("Can't change user status");
        }
        return $this;
    }
}
