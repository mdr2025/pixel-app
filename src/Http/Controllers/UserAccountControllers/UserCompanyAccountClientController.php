<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Resources\SystemAdminPanel\WorkSector\CompanyModule\CompanyAuth\TenantCompanyProfileResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\UserCompanyAccountServices\CompanyProfileGettingService\CompanyProfileGettingClientService;
use PixelApp\Services\UserCompanyAccountServices\CompanyProfileUpdatingService\CompanyProfileUpdatingClientService;
use PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin\CompanyChangeDefaultAdminServerService;
use PixelApp\Services\UserCompanyAccountServices\CompanyBranchesListServices\CompanyBranchesListClientService;
use PixelApp\Services\UserCompanyAccountServices\BranchStatusChangingServices\BranchStatusChangingClientService;
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

    public function updateAdminInfo()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyChangeDefaultAdminServerService::class);
        //for now only
        return (new $service())->update();
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

}
