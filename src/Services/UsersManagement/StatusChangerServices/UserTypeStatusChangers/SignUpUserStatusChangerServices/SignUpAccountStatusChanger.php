<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Exception;
use PixelApp\Services\UsersManagement\StatusChangerServices\AccountStatusChanger;
use Illuminate\Http\Request;

abstract class SignUpAccountStatusChanger extends AccountStatusChanger
{  
    protected function getRequest()  :Request
    {
        return request();
    }
    protected function mergeStatusValueToRequest(string $statusValue) : void
    {
        $request = $this->getRequest();
        $dataToMerge = array_merge($request->all() , ["status" => $statusValue]);
        $request->merge($dataToMerge);
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


}
