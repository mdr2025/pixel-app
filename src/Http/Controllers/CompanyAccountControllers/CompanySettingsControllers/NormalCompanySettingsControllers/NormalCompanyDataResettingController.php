<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\NormalCompanySettingsControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyDataResettingService\NormalCompanyDataResettingService;
use PixelApp\Services\PixelServiceManager;

class NormalCompanyDataResettingController extends Controller
{ 
   
    public function resetData() : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(NormalCompanyDataResettingService::class);
        return (new $service)->resetData();
    }
  
  
}
