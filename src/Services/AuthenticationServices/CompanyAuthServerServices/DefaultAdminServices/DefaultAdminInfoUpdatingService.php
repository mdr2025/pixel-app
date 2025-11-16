<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use Illuminate\Support\Facades\Log;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\DefaultAdminInfoUpdatingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\PixelServiceManager;

/**
 * to check sync later
 */
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
        return "Failed To Update The default admin info !";
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

    protected function onAfterDbTransactionStart(): void
    {
        $this->fillTenantCompanyDefaultAdminBeforeUpdating();
    }

    protected function getLoggingContext() : array
    {
        return [
            "admin_id" => $this->Model->getKey(),
            "company_id" => $this->Model->tenant->getKey()
        ];
    }

    protected function logOperationFailing() : void
    {
        Log::error( $this->getModelUpdatingFailingErrorMessage() , $this->getLoggingContext() );
    }

    protected function logOnSuccess() : void
    {
        Log::info($this->getModelUpdatingSuccessMessage() , $this->getLoggingContext());
    }

    protected function doBeforeSuccessResponding() : void
    {
        $this->logOnSuccess();
    }
    
    protected function doBeforeErrorResponding(?Exception $e = null) : void
    {
        $this->logOperationFailing();    
        parent::doBeforeErrorResponding($e);
    }
    
}
