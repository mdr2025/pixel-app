<?php

namespace PixelApp\Services\UserCompanyAccountServices\UserProfileUpdatingServices;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use PixelApp\Events\TenancyEvents\TenantModelDataSyncNeedEvent;
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

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function PasswordValueHandler(array $data): void
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
            $this->emailChanger = (new EmailChanger())->setData($data)->setAuthenticatable($this->Model);
        }
        return $this->emailChanger;
    }

    protected function prepareModelForDataSync() : void
    {
        /**
         * @todo later 
         */
        if($this->Model->isDirty() && $this->Model->canSyncData())
        {
            $this->Model->setOriginalIdentifierValue();
        }
    }
    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function checkUserEmailChanging(array $data): void
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

        $this->PasswordValueHandler($currentDataRow);
        $this->checkUserEmailChanging($currentDataRow);
        $this->prepareModelForDataSync();
   }

    /**
     * @throws Exception
     */
    protected function fireTenantModelDataSyncNeedEvent() : void
   {
       if( $this->Model->wasChanged() && $this->Model->canSyncData())
       {
           event( new TenantModelDataSyncNeedEvent($this->Model) );
       }
   }
    /**
     * @throws Exception
     */
    protected function doBeforeSuccessResponding(): void
   {
       $this->emailChanger->fireCommittingEvents();
       $this->fireTenantModelDataSyncNeedEvent();
   }

}
