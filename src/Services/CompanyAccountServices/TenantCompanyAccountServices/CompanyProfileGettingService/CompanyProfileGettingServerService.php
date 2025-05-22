<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileGettingService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\TenantCompanyProfileResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany;
use PixelApp\Models\CompanyModule\TenantCompany; 
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileGettingService\CompanyProfileGettingBaseService;

class CompanyProfileGettingServerService  extends CompanyProfileGettingBaseService
{ 
 
    protected function fetchCompany() : ?PixelCompany
    {
        return $this->fetchTenantByDomain();
    }
    
    protected function getResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType( TenantCompanyProfileResource::class );
    }

    protected function initResource(PixelCompany $company) : JsonResource
    {
        $resourceClass = $this->getResourceClass();
        return new $resourceClass($company);
    }

    protected function getSuccessResponse(PixelCompany $company) : JsonResponse
    {
        return Response::success([
                    "item" => $this->initResource($company)->toArray( $this->getRequest() )
                ]);
    }

    protected function getErrorResponse() : JsonResponse
    {
        return Response::error("There is no such tenant company has this domain stored in the database !" , []);
    }
    
    protected function getRequest() : Request
    {
        return request();
    }

    protected function getCompanyDomainQueryString() : string
    {
        $request = $this->getRequest();
        $request->validate(["company_domain" => ["required" , "string"]]);
        return $request->query("company_domain");
    }

    
    protected function initCompanyFetchingService() : CompanyFetchingService
    {
        $serviceClass = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return new $serviceClass;
    }

    protected function fetchTenantByDomain() : ?TenantCompany
    {
        $companyDomain = $this->getCompanyDomainQueryString();

        return $this->initCompanyFetchingService()->fetchTenantCompany($companyDomain);
    } 
     
}
