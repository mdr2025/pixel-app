<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices;
 
use CRUDServices\Traits\ResponseHelpers;
use Exception;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\DefaultAdminInfoUpdatingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\CoreServices\DataSyncingApiPort;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Traits\GeneralValidationMethods;
use Throwable;
 

class DefaultAdminInfoSyncingPort extends DataSyncingApiPort
{ 

    protected TenantCompany $tenant;

    use GeneralValidationMethods , ResponseHelpers;
 

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(DefaultAdminInfoUpdatingRequest::class);
    }

    public function sync() : JsonResponse
    {
        try
        {
            $this->initValidator()->validateRequest()->setRequestData();

            $this->setTenantCompany()->syncDefaultAdminData();
            
            return Response::success( $this->getSuccessedResponseData() , $this->getDataSyncingSuccessMessage());

        }catch(Throwable $e)
        {
            return $this->errorRespondingHandling($e) ;
        }
    }
     
    protected function initDefaultAdminNewModel() : CompanyDefaultAdmin
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
        return new $modelClass;
    }

    protected function syncDefaultAdminData() : void
    {
        $defaultAdmin = $this->initDefaultAdminNewModel();

        if($defaultAdmin instanceof TrustedAttributesHandlerModel)
        {
            $defaultAdmin->handleModelAttrs($this->data);
        }else
        {
            $defaultAdmin->forceFill($this->data);
        }
        
        if(!$defaultAdmin->save())
        {
            throw new Exception( $this->getDataSyncingFailingErrorMessage() );
        }
    }

    
    protected function removeRequestDataCompanyDomain() : void
    {
        /**
         * to avoid using it in default admin force filling
         * it is only required to fetch the comapny before operation start
         */
        unset($this->data["company_domain"]);
    }

    protected function fetchCompanyByDomain() : TenantCompany
    {
        $fetchingService = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return (new $fetchingService)->fetchTenantCompany($this->data["company_domain"]);
    }
  
    protected function setTenantCompany() : self
    {
        if($tenant = $this->fetchCompanyByDomain())
        {
            $this->tenant = $tenant;
            $this->removeRequestDataCompanyDomain();

            return $this;
        }

        throw new Exception("Failed to fetch the tenant company ... There is no such company has this domain !");
    }

    protected function getSuccessedResponseData() : array
    {
        return [];
    }
   
    protected function getDataSyncingFailingErrorMessage(): string
    {
        return "Failed to sync default admin information.";
    }

    protected function getDataSyncingSuccessMessage(): string
    {
        return "The default admin information has been successfully synchronized!";
    }


}
