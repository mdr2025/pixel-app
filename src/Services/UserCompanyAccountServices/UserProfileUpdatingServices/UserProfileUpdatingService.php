<?php

namespace PixelApp\Services\UserCompanyAccountServices\UserProfileUpdatingServices;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserAccountRequests\UpdateProfileRequest;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\EmailChanger;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;

/**
 * @property PixelUser | NeedsCentralDataSync $Model
 */
class UserProfileUpdatingService extends UpdatingService
{
    protected ?EmailChanger $emailChanger = null;

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdateProfileRequest::class);
    }

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update Your Profile !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "Your Profile Updated Successfully !";
    }

    protected function catchModelEmailOldValue() : void
    {
        $emailKey =  $this->Model->getEmailColumnName();
        $this->Model->catchKeyValueTemporarlly($emailKey);
    }

    protected function onAfterDbTransactionStart(): void
    {
        $this->catchModelEmailOldValue();
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function HandlePasswordValue(array $data): void
    {
        (new PasswordChanger())->setData($data)->setAuthenticatable($this->Model)->changeAuthenticatableProp();
    }

    /**
     * @throws Exception
     */
    protected function initEmailChanger(array $data = []) : EmailChanger
    {
        if(!$this->emailChanger)
        {
            $this->emailChanger = (new EmailChanger($this->Model ))->setData($data) ;
        }
        return $this->emailChanger;
    } 
    
    protected function HandleUserEmailChanging(array $data): void
    {
        $this->initEmailChanger($data)->changeAuthenticatableProp();
    }

    /**
     * @throws Exception
     */
    protected function doBeforeSavingCurrentModelProps(array $currentDataRow = []): void
   {
        /** Generate a new name frm first & last names after filling the Model's data */
        $this->Model->generateName();

        $this->HandlePasswordValue($currentDataRow);
        $this->HandleUserEmailChanging($currentDataRow); 
   }

    /**
     * @throws Exception
     */
   protected function syncTenancyData() : void
   {
       if( $this->Model->wasChanged() )
       {
           PixelTenancyManager::handleTenancySyncingData($this->Model);
       }
   }

   protected function fireEmailChangingEvent() : void
   {
        //will fire an event f email has changed only
        $this->emailChanger->fireCommittingDefaultEvents();
   }

    /**
     * @throws Exception
     */
    protected function doBeforeSuccessResponding(): void
   {
       $this->fireEmailChangingEvent();
       $this->syncTenancyData();
   }

}
