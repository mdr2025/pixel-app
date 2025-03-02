<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\CompanyFetchingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources\TenantCompanyResource;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\Traits\GeneralValidationMethods;

class CompanyFetchingService
{
    use GeneralValidationMethods;

    protected string $companyDomain;
    function __construct()
    {
        
    } 
    protected function setCompanyDomain(?string $companyDomain = null) : void
    {
        $this->companyDomain = $companyDomain ?? $this->getRequestCompanyDomain();
    }

    protected function getRequestCompanyDomain() : string
    {
        return $this->data["company_domain"] ;
    }

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(CompanyFetchingRequest::class);
    }

    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }

    protected function handleValidateionOperations() : void
    {
        $this->initValidator()->validateRequest()->setRequestData();
    }

    protected function fetchTenantComopanyModel() : ?TenantCompany
    {
        return $this->getTenantCompanyModelClass()::where('domain' , $this->companyDomain)->first();
    }

    public function getTenantCompanyDomainResponse() : JsonResponse
    {
        $this->handleValidateionOperations();
        $this->setCompanyDomain();
        if($tenant = $this->fetchTenantComopanyModel())
        {
            $resource = PixelHttpResourceManager::getResourceForResourceBaseType(TenantCompanyResource::class);
            $data = (new $resource($tenant))->toArray(request());
            return Response::success($data);
        }
        return Response::error("FAiled to found a tenant company has this domain");
    }

    public function fetchTenantCompany(string $companyDomain) : ?TenantCompany
    {
        $this->setCompanyDomain($companyDomain);
        return $this->fetchTenantComopanyModel();
    }
}
