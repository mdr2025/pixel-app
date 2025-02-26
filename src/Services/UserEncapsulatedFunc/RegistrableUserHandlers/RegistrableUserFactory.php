<?php

namespace PixelApp\Services\UserEncapsulatedFunc\RegistrableUserHandlers;

use Exception;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\RegistrableUserRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;

class RegistrableUserFactory
{
    use GeneralValidationMethods;

    protected array $userData = [];
    protected bool $passwordHashing = true;
    protected bool $dataValidation = false;
    protected PixelUser $user;

    protected function getRequestFormClass() : string
    {
        return RegistrableUserRequest::class;
    }

    public function __construct(array $userData )
    {
        $this->setUserData($userData);
    }

    public function setUserData(array $userData = []) : self
    {
         $this->data = $userData;
         return $this;
    }
    public function enablePasswordHashing()  :self
    {
        $this->passwordHashing = true;
        return $this;
    }

    /**
     * @return $this
     * it is useful when you try to get a User Model with the hashed password that has already hashed before by this factory class
     */
    public function disablePasswordHashing()  :self
    {
        $this->passwordHashing = false;
        return $this;
    }

    public function enableDataValidation()  :self
    {
        $this->dataValidation = true;
        return $this;
    }

    public function disableDataValidation()  :self
    {
        $this->dataValidation = false;
        return $this;
    }
    /**
     * @throws Exception
     */
    protected function processPassword() : self
    {
        if($this->passwordHashing)
        {
            $passwordChanges = (new PasswordChanger())->setData($this->data)->getPropChangesArray();
            $this->data = array_merge($this->data , $passwordChanges);
        }
        return $this;
    }

    protected function showUserPassword()  :self
    {
        $this->user->makeVisible("password");
        return $this;
    }

    protected function generateUserName() : self
    {
        $this->user->generateName();
        return $this;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function fillUser()  : self
    {
        $userModelClass = $this->getUserModelClass();
        $this->user = new $userModelClass($this->data);
        return $this;
    }

    protected function validateData() : self
    {
        if($this->dataValidation)
        {
            $this->initValidator()->changeRequestData($this->userData)->validateRequest()->setRequestData();
        }
        return $this;
    }
    /**
     * @throws Exception
     */
    public function makeUser() : PixelUser
    {
        $this->validateData()->processPassword()->fillUser()->generateUserName()->showUserPassword();
        return $this->user;
    }
}
