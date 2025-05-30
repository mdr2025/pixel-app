<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices;

use Exception;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\AccountStatusChanger;
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
        if(!$this->model->isSignUpAccount())
        {
            throw new Exception("Can't change account status");
        }
        return $this;
    }


}
