<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\TenantCompanyAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse; 
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileGettingService\CompanyProfileGettingClientService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileUpdatingService\CompanyProfileUpdatingClientService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyBranchesListServices\CompanyBranchesListClientService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\BranchStatusChangingServices\BranchStatusChangingClientService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyUpdateAdmin\TenantCompanyDefaultAdminChangingService;
use PixelApp\Services\PixelServiceManager; 

class UserCompanyAccountClientController extends Controller
{

    public function companyProfile() : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyProfileGettingClientService::class);
        return (new $service())->getResponse();
    }
  
    /**
     * @throws Exception
     */
    public function updateCompanyProfile(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyProfileUpdatingClientService::class);
        return (new $service())->getResponse(); 
    }
  
    public function companyBranchList()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyBranchesListClientService::class);
        return (new $service)->getResponse();
    }

    public function changeBranchStatus($id)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchStatusChangingClientService::class);
        return (new $service($id))->getResponse();
    }

    public function changeDefaultAdmin()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantCompanyDefaultAdminChangingService::class);
        return (new $service())->update();
    }
}
