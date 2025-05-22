<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\NormalCompanyAccountControllers;
 
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyProfileGettingService\NormalCompanyProfileGettingService;
use PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyProfileUpdatingService\NormalCompanyProfileUpdatingService;
use PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyUpdateAdmin\NormalCompanyDefaultAdminChangingService;
use PixelApp\Services\PixelServiceManager; 

class NormalCompanyAccountController extends Controller
{

    public function companyProfile() : JsonResponse
    {    
        $service = PixelServiceManager::getServiceForServiceBaseType(NormalCompanyProfileGettingService::class);
        return (new $service())->getResponse(); 
    }
  
    /**
     * @throws Exception
     */
    public function updateCompanyProfile(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(NormalCompanyProfileUpdatingService::class);
        return (new $service())->update(); 
    }
      
    public function changeDefaultAdmin()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(NormalCompanyDefaultAdminChangingService::class);
        return (new $service())->update();
    }

}
