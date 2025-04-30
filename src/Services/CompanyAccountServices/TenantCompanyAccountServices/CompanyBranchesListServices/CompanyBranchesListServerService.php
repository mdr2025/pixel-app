<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyBranchesListServices;
 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Filters\MultiFilters; 
use Stancl\Tenancy\Contracts\Tenant;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\PixelServiceManager;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyBranchesListServerService 
{  
    protected function fetchTenantByDomain(?string $companyDomain = null) : ?Tenant
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return (new $service())->fetchTenantCompany($companyDomain);
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
    
    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }

    protected function initTenantCompanySpatieQueryBuilder() : QueryBuilder
    {
        return QueryBuilder::for( $this->getTenantCompanyModelClass());
    }
 
    public function list() : JsonResponse
    {  
        $request = $this->getRequest();
        $companyDomain = $this->getCompanyDomainQueryString($request);
        if($tenant = $this->fetchTenantByDomain($companyDomain))
        {
            $companyId = $tenant->getTenantKey();
            $branches  = $this->initTenantCompanySpatieQueryBuilder()
                              ->allowedFilters([
                                    AllowedFilter::exact("status", "main_company_PixelApproved_status"),
                                    AllowedFilter::custom('name',new MultiFilters([
                                        'name',
                                        'defaultAdmin.email'
                                    ])),
                                    "company_id"
                                ])
                              ->where('parent_id', $companyId)
                              ->with(['country' , 'defaultAdmin'])
                              ->paginate(request()->pageSize ?? 10);
           return response()->json(['list' => $branches]);

        }else{
            return Response::error("There is no tenant company has this domain" , []);
        }
        
    }
}
