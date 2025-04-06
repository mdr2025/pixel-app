<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces;

interface StatusChangeableAccount
{
    public function getSignUpAccountStatusChangableValues() : array;
    public function getAcceptedAccountStatusChangableValues() : array;
    public function isSystemMemberAccount()  :bool;
    public function isSignUpAccount() : bool;
    public function getApprovingStatusValue()  :string;
    public function getAccountApprovingProps();
    public function getDefaultStatusValue() : string; 
}