<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers;

use PixelApp\Exceptions\ExceptionTypes\JsonException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
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
        return new $changerClass(); 
    }

    protected function setPropChangerMainProps(UserSensitivePropChanger $propChanger) : void
    {
        $propChanger->setAuthenticatable($this->user);

        if($propChanger instanceof ExpectsSensitiveRequestData)
        {
            $propChanger->setData($this->data);
        }
    }

    protected function throwInvalidPropChangerChildClassException(string $changerClass) : void
    {
        throw new InvalidArgumentException(
            sprintf('Class "%s" must extend %s.', $changerClass, UserSensitivePropChanger::class)
        );
    }
    protected function throwInvalidPropChangerChildObjectException() : void
    {
        throw new InvalidArgumentException(
            'User Property Changer must be an instance of ' . UserSensitivePropChanger::class
        );
    }
    /**
     * @param string|UserSensitivePropChanger $changer
     * @return UserSensitivePropChanger
     * @throws Exception
     */
    protected function getChangerInstance(string |UserSensitivePropChanger $changer) :UserSensitivePropChanger
    {
        if (is_string($changer)) 
        {
            if (!is_subclass_of($changer, UserSensitivePropChanger::class))
            {
                $this->throwInvalidPropChangerChildClassException($changer);
            }

            $changer = $this->initPropChanger($changer);
        }

        if (!$changer instanceof UserSensitivePropChanger) {
            $this->throwInvalidPropChangerChildObjectException();
        }

        $this->setPropChangerMainProps($changer);

        return $changer;
    }

    /**
     * @param string|UserSensitivePropChanger $propChanger
     * @return $this
     * @throws Exception
     */
    public function changePropOrFail(string |UserSensitivePropChanger $propChanger) : self
    {
        $this->getChangerInstance( $propChanger )->changeAuthenticatablePropOrFail();
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
        $this->getChangerInstance( $propChanger )->changeAuthenticatableProp();
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
