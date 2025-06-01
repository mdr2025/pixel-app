<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\FecthApprovedTenantCompanyIDSRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class ApprovedTenantCompaniesFetchingService extends AdminPanelConnectingClientService
{
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new FecthApprovedTenantCompanyIDSRouteIdentifierFactory();
    }
  
    public function getResponseArray(JsonResponse $response) : array
    {
        return parent::getResponseArray($response)["data"];
    }

    protected function initApprovedTenantsCollection(array $tenantIds) : Collection
    {
        $collection = collect();

        foreach($tenantIds as $id)
        {
            if($id = intval($id) !== 0)
            {
                $tenant = PixelTenancyManager::getRunableTenant($id);
                $collection->add($tenant);
            }
        }

        return $collection;
    }

    public function fetchApprovedTenantCompanies() : ?Collection
    {
        $response = $this->getResponse(); 
        $tenantIds = $this->getResponseArray($response);

        return $this->initApprovedTenantsCollection($tenantIds);
        
    }
}
