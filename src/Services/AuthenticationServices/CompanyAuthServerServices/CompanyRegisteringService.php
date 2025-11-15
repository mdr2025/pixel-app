<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\SingleRowStoringService;
use Exception;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyRegistered;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\MainCompanyRegisterRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\UserEncapsulatedFunc\RegistrableUserHandlers\RegistrableUserFactory;
  
/**
 * @property TenantCompany $Model
 */
 class CompanyRegisteringService extends SingleRowStoringService
{
 
    protected function anyAdditionActions(): void {
        // $crNo = $this->data['related_cr_no'] ?? null;
        // if ($crNo){
        //     $companyParent = $this->getModelClass()::where('cr_no', $crNo)->firstOrFail();
        //     $this->data['parent_id'] = $companyParent->id;
        // }
    }
     
    protected function getModelClass(): string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();;
    }

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed to creat a new company !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "Your Company account has been created successfully ... Please verify your default admin 's email address from the link you have got into your email !";
    }

    protected function initRegistrableUserDataFactory(array $userData = []): RegistrableUserFactory
    {
        return new RegistrableUserFactory($userData);
    }
  
    protected function injectCompanyMobile() : void
    {
        $this->data["mobile"] = $this->data["defaultAdmin"]["mobile"];
    }

    protected function injectAdminNationalityId() : void
    {
        $this->data["defaultAdmin"]["nationality_id"] = $this->data["country_id"];
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function overrideDefaultAdminData(): void
    {
        /** Overriding defaultAdmin data with registrable admin processed data */
        $this->data["defaultAdmin"] = $this->initRegistrableUserDataFactory($this->data["defaultAdmin"])
                                           ->makeUser()
                                           ->toArray();

        $this->injectAdminNationalityId();
 
    }


    /**
     * @throws Exception
     */
    protected function onAfterDbTransactionStart(): void
    {
        $this->anyAdditionActions();
        $this->overrideDefaultAdminData();
        $this->injectCompanyMobile();
    }

    protected function fireRegisteredEvent() : void
    {
        event(new TenantCompanyRegistered($this->Model));
    }
    
    protected function doBeforeSuccessResponding(): void
    {
        $this->fireRegisteredEvent();
    }

     protected function getRequestClass(): string
     {
        return PixelHttpRequestManager::getRequestForRequestBaseType(MainCompanyRegisterRequest::class);
     }
 }
