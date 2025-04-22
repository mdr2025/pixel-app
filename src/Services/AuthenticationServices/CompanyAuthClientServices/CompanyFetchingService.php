<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\FecthTenantCompanyRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\Interfaces\TrustedRelationAttributesHandlerModel;
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
        return $response->getData(true)["data"];
    }

    protected function getResponseRelationsData(array $attributes) : array
    {
        return $attributes["relations"] ?? [];
    }

    protected function getResponseTenantData(array $attributes) : array
    {
        return $attributes["tenant"] ?? [];
    }

    protected function setTenantRelations(Tenant | Model $tenant , array $attributes) : void
    {
        $relationsData = $this->getResponseRelationsData($attributes);

        if($tenant instanceof TrustedRelationAttributesHandlerModel)
        {
            $tenant->handleRelationsAttrs($relationsData);
        }
    }
    
    protected function initNewTenant(array $attributes) : Tenant | Model 
    {
        $attributes = $this->getResponseTenantData($attributes);
        if(empty($attributes))
        {
            throw new Exception("Missed data ... no tenant compan yis found !");
        }

        $tenantClass = PixelTenancyManager::getTenantCompanyModelClass(); 
        $tenant = new $tenantClass();

        if($tenant instanceof TrustedAttributesHandlerModel)
        {
            $tenant->handleModelAttrs($attributes);
        
        }else
        {
            $tenant->Fill($attributes);
        }

        return $tenant;
    }

    public function fetchTenantCompany() : ?Tenant
    {
        $response = $this->getResponse(); 
        $attributes = $this->getResponseArray($response);
        
        if(!empty($attributes))
        { 
            $tenant = $this->initNewTenant($attributes);
            $this->setTenantRelations($tenant , $attributes);
            
            dd($tenant);
            return $tenant;
        }
        return null;
        
    }
}
