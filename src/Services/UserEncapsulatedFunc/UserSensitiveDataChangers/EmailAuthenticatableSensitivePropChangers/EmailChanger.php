<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers;

use PixelApp\Events\EmailAuthenticatableEvents\EmailChangingEvent;
use Exception;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class EmailChanger extends EmailAuthenticatableSensitivePropChanger implements ExpectsSensitiveRequestData
{
    use ExpectsSensitiveRequestDataFunc;

    private bool $emailHasChange = false;
    private ?VerificationPropsChanger $verificationPropsChanger = null;
    protected string $oldEmail = "";
    protected ?string $emailNewValue  = null;

    public function getPropName() : string
    {
        return $this->authenticatable->getEmailColumnName();
    }

    public function getPropRequestKeyDefaultName(): string
    {
        return 'email';
    }

    /**
     * @throws Exception
     */
    protected function getVerificationPropsChanger() : VerificationPropsChanger
    {
        if(!$this->verificationPropsChanger)
        {
            /** @var VerificationPropsChanger|UserSensitivePropChanger|null $verificationPropsChanger */
            $verificationPropsChanger = new VerificationPropsChanger($this->authenticatable) ;
            $this->verificationPropsChanger = $verificationPropsChanger;
        }
        return $this->verificationPropsChanger;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getVerificationPropsChanges() : array
    {
        return $this->getVerificationPropsChanger()->getPropChangesArray();
    }

    /**
     * @throws Exception
     */
    protected function requireUserToVerifyEmail() : self
    {
        $this->getVerificationPropsChanger()->requireToVerify();
        return $this;
    }

    protected function setUserOldEmailAttr(): self
    {
        $this->oldEmail = $this->authenticatable->{ $this->getPropName()  };
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function DoesEmailHaveChange() : bool
    {
        return  $this->emailHasChange 
        =
        $this->emailNewValue 
        &&
        $this->emailNewValue !== $this->authenticatable->{ $this->getPropName() };
    }

    protected function setEmailNewValue() : void
    {
        $this->emailNewValue = $this->getPropNewRequestValue();
    }

    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {  
        $this->setEmailNewValue();

        if($this->DoesEmailHaveChange())
        {
            $this->requireUserToVerifyEmail()->setUserOldEmailAttr();
            return array_merge(
                        $this->composeChangesArray( $this->getPropNewRequestValue() ) ,
                        $this->getVerificationPropsChanges()
                   );
        }
        return [];
    }
 
    public function callOnEmailChange(callable $callback) : void
    {
        if($this->emailHasChange)
        {
            call_user_func($callback , [$this->emailNewValue , $this->oldEmail]);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function fireCommittingDefaultEvents() : void
    {
        /**
         * Must check user because this method can be used before getPropChangesArray or changeUserProp methods
         * So if user is not set an unexpected failing will happen
         */
        if($this->emailHasChange && $this->checkAuthenticatable())
        {
            event(new EmailChangingEvent($this->authenticatable , $this->oldEmail));
        }
    }

}
