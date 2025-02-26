<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use Illuminate\Support\Facades\Hash;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class PasswordChanger extends UserSensitivePropChanger implements ExpectsSensitiveRequestData
{
    use ExpectsSensitiveRequestDataFunc;

    protected bool $needToCheckOldPassword = false;
    protected string $oldPasswordRequestKeyName = "old_password";
    protected string $newPasswordRequestKeyName = "new_password";

    /**
     * @param string $oldPasswordRequestKeyName
     * @return $this
     */
    public function setOldPasswordRequestKeyName(string $oldPasswordRequestKeyName): self
    {
        $this->oldPasswordRequestKeyName = $oldPasswordRequestKeyName;
        return $this;
    }
    public function getPropName() : string
    {
        return 'password';
    }
    public function getPropRequestKeyDefaultName(): string
    {
        return 'password';
    }
    public function mustCheckOldPassword() : self
    {
        $this->needToCheckOldPassword = true;
        return $this;
    }

    protected function getOldPasswordRequestValue() : string
    {
        return $this->data[ $this->oldPasswordRequestKeyName ] ?? "";
    }
    protected function getNewPasswordRequestValue() : string
    {
        return $this->data[ $this->newPasswordRequestKeyName ] ?? "";
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function checkOldPassword() : void
    {
        $this->checkAuthenticatable();
        if(!Hash::check( $this->getOldPasswordRequestValue() , $this->authenticatable->{ $this->getPropName() } ))
        {
            throw new Exception("Invalid old Password .. Failed to change password");
        }
    }
    protected function checkNewPasswordChanging() :void
    {
        if(Hash::check( $this->getNewPasswordRequestValue() , $this->authenticatable->{ $this->getPropName() } ))
        {
            throw new Exception("The new password cannot be the same as the old password ");
        }
    }
    protected function hashPassword(?string $value): mixed
    {
        if($value)
        {
            $value = Hash::make(  $value );
        }
        return $value ;
    }
    /**
     * @return array
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        if($this->needToCheckOldPassword) 
        {
            $this->checkOldPassword();
            $this->checkNewPasswordChanging();
        }

        /**
         * If No Exception Is thrown ... Password changing operation will continue
         */
        $password =  $this->getPropNewRequestValue();
        $hashedPassword = $this->hashPassword($password);
        return $this->composeChangesArray( $hashedPassword );
    }

}
