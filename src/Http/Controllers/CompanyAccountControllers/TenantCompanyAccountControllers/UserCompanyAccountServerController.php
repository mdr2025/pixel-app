<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\TenantCompanyAccountControllers;
 
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\TenantCompanyProfileResource;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileGettingService\CompanyProfileGettingServerService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileUpdatingService\CompanyProfileUpdatingServerService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyBranchesListServices\CompanyBranchesListServerService;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\BranchStatusChangingServices\BranchStatusChangingServerService;

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
