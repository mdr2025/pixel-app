<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use Illuminate\Database\Eloquent\Model; 
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;


/**
 * @property StatusChangeableAccount $authenticatable
 */
class StatusChanger extends UserSensitivePropChanger implements ExpectsSensitiveRequestData
{
    use ExpectsSensitiveRequestDataFunc;

    protected ?string $statusValue = null ;
     

    public function setAuthenticatable( ?Model $authenticatable): self
    {
        $this->isItStatusChangeableModel($authenticatable);
        return parent::setAuthenticatable($authenticatable);
    }

    protected function isItStatusChangeableModel(?Model $authenticatable) : void
    {
        if(! $authenticatable instanceof StatusChangeableAccount)
        {
            //for development only
            dd("The Authenticatable account wanted to change its status must implement StatusChangeableAccount interface");
        }
    }

    public function getPropName() : string
    {
        return 'status';
    }

    public function getPropRequestKeyDefaultName(): string
    {
        return 'status';
    }
 
    protected function getAuthenticatableDefaultStatus()  :string
    { 
        return $this->authenticatable->getDefaultStatusValue();
    }

    protected function setStatusDefaultValue() : void
    {
        if(!$this->statusValue)
        {
            $this->statusValue = $this->getAuthenticatableDefaultStatus();
        }
    }

    public function approve(Model $authenticatable) : self
    {
        $this->setAuthenticatable($authenticatable);
        $this->statusValue  = $this->authenticatable->getApprovingStatusValue();
        return $this;
    }

    protected function getAcceptedAuthenticatableProps() : array
    {
        return $this->authenticatable->getAccountApprovingProps();
    }

    protected function getApprovedAuthenticatableChangesArray() : array
    {
        return [
                    $this->getPropName() => $this->authenticatable->getApprovingStatusValue() ,
                    ...$this->getAcceptedAuthenticatableProps()
               ];
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function handleStatusRequestValue() :string
    {
        $status = $this->getPropNewRequestValue();
        return $status ?? throw new Exception("Status value isn't sent");
    }

    public function isChangeAllowableValue() : bool
    {
        return $this->statusValue != 'pending';
    }
    /**
     * @throws Exception
     */
    protected function prepareStatusValue() : void
    {
        $this->setStatusDefaultValue();
        
        if($this->statusValue == 'pending')
        {
            /**  isn't set manually by approve method ... so need to be set from request data */
            $this->statusValue = $this->handleStatusRequestValue();
        }
    }

    /**
     * @return int
     */
    public function getStatusValue(): int
    {
        return $this->statusValue;
    }
    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        $this->prepareStatusValue();
        if($this->isChangeAllowableValue())
        {
            return $this->statusValue === 'active' ?
                   $this->getApprovedAuthenticatableChangesArray() :
                   $this->composeChangesArray( $this->statusValue  );
        }
        return [];
    }

}
