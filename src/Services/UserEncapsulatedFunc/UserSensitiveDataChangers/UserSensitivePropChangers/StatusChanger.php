<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class StatusChanger extends UserSensitivePropChanger implements ExpectsSensitiveRequestData
{
    use ExpectsSensitiveRequestDataFunc;

    protected int $statusValue = 0;
    public function getPropName() : string
    {
        return 'status';
    }
    public function getPropRequestKeyDefaultName(): string
    {
        return 'status';
    }
    public function approve() : self
    {
        $this->statusValue  = 'active';
        return $this;
    }

    protected function getAcceptedUserProps() : array
    {
        return [
            "accepted_at" => now(),
            "user_type" => "user"
        ];
    }
    protected function getApprovedUserChangesArray() : array
    {
        return [
                    $this->getPropName() => 'active' ,
                    ...$this->getAcceptedUserProps()
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

    /**
     * @throws Exception
     */
    protected function prepareStatusValue() : void
    {
        if($this->statusValue == 'pending')
        {
            /**  isn't set manually by approve method ... so need to be set from request data */
            $this->statusValue = $this->handleStatusRequestValue();
        }
    }

    public function isChangeAllowableValue() : bool
    {
        return $this->statusValue != 'pending';
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
                $this->getApprovedUserChangesArray() :
                $this->composeChangesArray( $this->statusValue  );
        }
        return [];
    }

}
