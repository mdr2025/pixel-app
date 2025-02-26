<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\FecthTenantCompanyRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
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

    public function fetchTenantCompany() : ?Tenant
    {
        $response = $this->getResponse();
        $attributes = $this->getResponseArray($response);
        if(!empty($attributes))
        {
            $tenantClass = PixelTenancyManager::getTenantCompanyModelClass();
            new $tenantClass($attributes);
        }
        return null;
        
    }
}
