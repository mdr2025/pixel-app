<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;

abstract class UserSensitivePropChanger
{
    protected EmailAuthenticatable | Model|null $authenticatable = null;

    abstract  public function getPropName() : string;
    abstract public function getPropChangesArray( ) : array;

    /**
     * @param Model|null $authenticatable
     * @return $this
     * @throws Exception
     */
    public function setAuthenticatable(Model $authenticatable): self
    { 
        $this->authenticatable = $authenticatable;
        return $this;
    }

    protected function fillNotFillableSensitiveColumns(array $data) : void
    {
        foreach ($data as $column => $value)
        {
            $this->authenticatable->{$column} = $value;
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function checkAuthenticatable()  : bool
    {
        return (bool) ($this->authenticatable ?? throw new Exception("Authenticatable is not set ... Failed to change its props"));
    }
    /**
     * @return void
     * @throws Exception
     */
     public function changeAuthenticatableProp() : void
    {
        $this->checkAuthenticatable();
        $this->fillNotFillableSensitiveColumns($this->getPropChangesArray());
    }

    public function checkAuthenticatableChanging(array $attrs = []) : bool
    {
        if(empty($attrs) && $this instanceof ExpectsSensitiveRequestData)
        {
            $attrs = array_keys( $this->getData() );
        }
        return $this->authenticatable->isDirty( $attrs);
    }

    /**
     * @return void
     * @throws Exception
     */
     public function changeAuthenticatablePropOrFail() : void
    {
        $this->changeAuthenticatableProp();
        if(!$this->checkAuthenticatableChanging())
        {
            throw new Exception("Failed to change Authenticatable's " . $this->getPropName() . " property ");
        }
    }
}
