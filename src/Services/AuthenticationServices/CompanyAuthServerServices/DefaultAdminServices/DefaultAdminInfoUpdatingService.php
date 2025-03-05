<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\DefaultAdminInfoUpdatingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\PixelServiceManager;

class DefaultAdminInfoUpdatingService extends UpdatingService
{ 
    public function __construct()
    {
        parent::__construct($this->initDefaultAdminNewModel());
    }

    protected function initDefaultAdminNewModel() : CompanyDefaultAdmin
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
        return new $modelClass;
    }

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The The default admin info !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "The default admin info Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(DefaultAdminInfoUpdatingRequest::class);
    }

    protected function fetchCompanyByDomain() : TenantCompany
    {
        $fetchingService = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return (new $fetchingService)->fetchTenantCompany($this->data["company_domain"]);
    }

    protected function fillTenantCompanyDefaultAdminBeforeUpdating() : void
    {
        $defaultAdminAttrs = $this->fetchCompanyByDomain()->defaultAdmin->attributesToArray();
        $this->Model->forceFill($defaultAdminAttrs);
    }

    protected function doBeforeOperationStart(): void
    {
        $this->fillTenantCompanyDefaultAdminBeforeUpdating();
    }
    
}
