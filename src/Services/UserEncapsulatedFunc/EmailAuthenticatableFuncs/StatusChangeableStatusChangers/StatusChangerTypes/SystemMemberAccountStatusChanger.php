<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes;

use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserStatusUpdatingRequest; 
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\AccountStatusChanger;


abstract class SystemMemberAccountStatusChanger extends AccountStatusChanger
{ 
    /**
     * @return $this
     * @throws Exception
     * Protecting condition - For avoiding to call api to change status to a value used for another user type
     */
    protected function checkConditionsBeforeStart()  : self
    {
        if(! $this->model->isSystemMemberAccount())
        {
            throw new Exception("Can't change account status");
        }
        return $this;
    }
}
