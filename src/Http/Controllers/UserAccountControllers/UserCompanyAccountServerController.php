<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\TenantCompanyProfileResource;
use PixelApp\Services\UserCompanyAccountServices\CompanyProfileGettingService\CompanyProfileGettingServerService;
use PixelApp\Services\UserCompanyAccountServices\CompanyProfileUpdatingService\CompanyProfileUpdatingServerService;
use PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin\CompanyChangeDefaultAdminServerService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UserCompanyAccountServices\CompanyBranchesListServices\CompanyBranchesListServerService;
use PixelApp\Services\UserCompanyAccountServices\BranchStatusChangingServices\BranchStatusChangingServerService;
use Stancl\Tenancy\Contracts\Tenant;

class UserCompanyAccountServerController extends Controller
{

    public function companyProfile() : JsonResponse
    {    
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyProfileGettingServerService::class);
        return (new $service())->getResponse(); 
    }
  
    /**
     * @throws Exception
     */
    public function updateCompanyProfile(Request $request): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyProfileUpdatingServerService::class);
        $response = (new $service())->update();
        return $this->checkResponse($response, $request);
    }
    protected function checkResponse(JsonResponse $response, Request $request): JsonResponse
    {
        if ($response->getStatusCode() == 200) {
            $CompanyProfileDataResource = new TenantCompanyProfileResource(tenant());
            $data = $response->getData(true);
            $response->setData([...$data, "data" => $CompanyProfileDataResource->toArray($request)]);
        }
        return $response;
    }

    public function updateAdminInfo()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyChangeDefaultAdminServerService::class);
        //for now only
        return (new $service())->update();
    }
  
    public function companyBranchList()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyBranchesListServerService::class);
        return (new $service)->list();
    }

    public function changeBranchStatus( $id)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchStatusChangingServerService::class);
        return (new $service($id))->change(); 
    }

}
