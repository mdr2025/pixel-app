<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

abstract class EmailAuthenticatableSensitivePropChanger extends UserSensitivePropChanger
{

    public function setAuthenticatable(?Model $authenticatable): UserSensitivePropChanger
    {
        if(! $authenticatable instanceof EmailAuthenticatable)
        {
            throw new Exception("Authenticatable must implement EmailAuthenticatable interface");
        }
        return parent::setAuthenticatable($authenticatable);
    }
}
