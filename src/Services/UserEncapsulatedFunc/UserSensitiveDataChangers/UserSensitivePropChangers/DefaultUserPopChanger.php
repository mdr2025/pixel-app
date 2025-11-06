<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

 
use Exception;

class DefaultUserPopChanger extends UserSensitivePropChanger
{
    protected bool $defaultUser = false;

    public function getPropName() : string
    {
        return 'default_user';
    }
    public function convertToDefaultUser() : self
    {
        $this->defaultUser  = true;
        return $this;
    }
    public function convertToNormalUser()  :self
    {
        $this->defaultUser = false;
        return $this;
    }

    protected function getDefaultPropValue() : int
    {
        return $this->defaultUser ? 1 : 0;
    }
    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        return [ $this->getPropName() => $this->getDefaultPropValue() ];
    }

}
