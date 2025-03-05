<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\FecthTenantCompanyRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\PixelModelManager;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyFetchingService extends AdminPanelConnectingClientService
{
    protected string $companyDomain;
    public function __construct(string $companyDomain)
    {
        $this->setCompanyDomain($companyDomain);
    }
    public function  setCompanyDomain(string $companyDomain)
    {
        $this->companyDomain = $companyDomain;
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new FecthTenantCompanyRouteIdentifierFactory($this->companyDomain);
    }

    public function getResponse(): JsonResponse
    {
        $routeIdentifier = $this->makeAdminPanelRouteIdentifier();
        return $this->connectOnAdminPanel()->requestOnRoute($routeIdentifier);
    }
    
    public function getResponseArray(JsonResponse $response) : array
    {
        return $response->getData(true);
    }

    protected function initNewDefaultAdmin(array $attributes) : CompanyDefaultAdmin
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
        return new $modelClass($attributes["defaultAdmin"]);
    }
    protected function setTenantDefaultAdminRelation(Tenant | Model $tenant , array $attributes) : void
    {
        if(array_key_exists("defaultAdmin" , $attributes))
        {
            $tenant->setRelation("defaultAdmin" , $this->initNewDefaultAdmin($attributes));
        }
    }

    protected function setTenantRelations(Tenant | Model $tenant , array $attributes) : void
    {
        $this->setTenantDefaultAdminRelation($tenant , $attributes);
    }
    
    protected function initNewTenant(array $attributes) : Tenant | Model 
    {
        $tenantClass = PixelTenancyManager::getTenantCompanyModelClass();
        return new $tenantClass($attributes);
    }
    public function fetchTenantCompany() : ?Tenant
    {
        $response = $this->getResponse();
        $attributes = $this->getResponseArray($response);
        if(!empty($attributes))
        {
            $tenant = $this->initNewTenant($attributes);
            $this->setTenantRelations($tenant , $attributes);
            return $tenant;
        }
        return null;
        
    }
}
