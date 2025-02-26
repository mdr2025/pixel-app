<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers;

use PixelApp\Exceptions\JsonException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

class UserSensitiveDataChanger
{
    protected  PixelUser $user ;
    protected array $data;

    public function __construct(PixelUser $user , array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function getUser() : PixelUser
    {
        return $this->user;
    }

    protected function initPropChanger(string $changerClass) : UserSensitivePropChanger | ExpectsSensitiveRequestData
    {
        $changer = (new $changerClass())->setAuthenticatable($this->user);

        if($changer instanceof ExpectsSensitiveRequestData)
        {
            $changer->setData($this->data);
        }
        return $changer;
    }
    /**
     * @param string|UserSensitivePropChanger $changer
     * @return UserSensitivePropChanger
     * @throws Exception
     */
    protected function getChangeInstance(string |UserSensitivePropChanger $changer) :UserSensitivePropChanger
    {
        if($changer instanceof UserSensitivePropChanger) // if it is an object already
        {
            return $changer;
        }

        if(is_subclass_of($changer , UserSensitivePropChanger::class)) // if it is a class
        {
            return $this->initPropChanger($changer);
        }

        // if it is another type
        throw new Exception("User Property Changer must be instance of UserSensitivePropChanger class !");
    }
    /**
     * @param string|UserSensitivePropChanger $propChanger
     * @return $this
     * @throws Exception
     */
    public function changePropOrFail(string |UserSensitivePropChanger $propChanger) : self
    {
        $this->getChangeInstance( $propChanger )->changeAuthenticatablePropOrFail();
        return $this;
    }

    /**
     * @throws Exception
     */
    public function changePropsOrFail(array $propChangers): self
    {
        foreach ($propChangers as $propChanger)
        {
            $this->changePropOrFail($propChanger);
        }
        return $this;
    }
    /**
     * @param string|UserSensitivePropChanger $propChanger
     * @return $this
     * @throws Exception
     */
    public function changeProp(string | UserSensitivePropChanger $propChanger) : self
    {
        $this->getChangeInstance( $propChanger )->changeAuthenticatableProp();
        return $this;
    }

    /**
     * @param array $propChangers
     * @return $this
     * @throws Exception
     */
    public function changeProps(array $propChangers): self
    {
        foreach ($propChangers as $propChanger)
        {
            $this->changeProp($propChanger);
        }
        return $this;
    }

    /**
     * @return Model
     * @throws JsonException
     * @throws Exception
     */
    public function saveChanges(): Model
    {
        if($this->user->save()){return $this->user;}
        throw new JsonException("Failed To Update User's Sensitive Data !");
    }
}
