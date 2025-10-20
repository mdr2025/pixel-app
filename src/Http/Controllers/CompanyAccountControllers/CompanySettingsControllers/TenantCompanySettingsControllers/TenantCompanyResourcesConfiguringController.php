<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\TenantCompanySettingsControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringCancelingServices\TenantResourcesConfiguringCancelingServerService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringServices\TenantResourcesConfiguringServerService;
use PixelApp\Services\PixelServiceManager;

class TenantCompanyResourcesConfiguringController extends Controller
{ 
   
    public function configureTenantResources() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantResourcesConfiguringServerService::class);
        return (new $service)->configure();
    }

    public function cancelTenantResourcesConfiguring() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantResourcesConfiguringCancelingServerService::class);
        return (new $service)->cancel();   
    }
  
}
