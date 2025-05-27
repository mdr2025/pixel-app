<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\TenantCompanySettingsControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyDataResettingService\TenantCompanyDataResettingService;
use PixelApp\Services\PixelServiceManager;

class TenantCompanyDataResettingController extends Controller
{ 
   
    public function resetData() : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantCompanyDataResettingService::class);
        return (new $service)->resetData();
    }
}
