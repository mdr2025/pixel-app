<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyProfileGettingService;
 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\TenantCompanyProfileResource;
use Stancl\Tenancy\Contracts\Tenant;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
 
class CompanyProfileGettingServerService 
{ 
    protected function fetchTenantByDomain(?string $companyDomain = null) : ?Tenant
    {
        return (new CompanyFetchingService())->fetchTenantCompany($companyDomain);
    } 
    
    protected function getCompanyDomainQueryString(Request $request) : string
    {
        $request->validate(["company_domain" => ["required" , "string"]]);
        return $request->query("company_domain");
    }

    protected function getRequest() : Request
    {
        return request();
    }

    public function getResponse() : JsonResponse
    {
        $request = $this->getRequest();
        $companyDomain = $this->getCompanyDomainQueryString($request);
        if($tenant = $this->fetchTenantByDomain($companyDomain))
        {
            $CompanyProfileDataResource = new TenantCompanyProfileResource( $tenant );
            return Response::success([
                "item" => $CompanyProfileDataResource->toArray( $request )
             ]);

        }else{
            return Response::error("There is no tenant company has this domain" , []);
        }
        
    }
}
