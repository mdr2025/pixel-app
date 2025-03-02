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
use PixelAppCore\Services\PixelServiceManager;
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
  
    // public function companyBranchList()
    // {
    //     $companyId = tenant()->getTenantKey();
    //     $branches  = QueryBuilder::for(TenantCompany::class)
    //         ->allowedFilters([
    //             AllowedFilter::exact("status", "main_company_PixelApproved_status"),
    //             AllowedFilter::custom('name',new MultiFilters([
    //                 'name',
    //                 'defaultAdmin.email'
    //             ])),
    //             "company_id"
    //         ])
    //         ->where('parent_id', $companyId)
    //         ->with(['country' , 'defaultAdmin'])
    //         ->paginate(request()->pageSize ?? 10);
    //     return response()->json(['list' => $branches]);
    // }

    // public function changeBranchStatus(ChangeBranchCompanyStatusRequest $request , $id)
    // {
    //     $status = $request->get('status');
    //     $company = TenantCompany::find($id);
    //     if (!$company) {
    //         return Response::error(["Company not found."]);
    //     }

    //     if ($company->main_company_PixelApproved_status !== 'pending') {
    //         return Response::error(["Status cannot be changed."]);
    //     }

    //     $company->main_company_PixelApproved_status = $status;
    //     $company->save();
    //     return Response::success([], ["Status changed successfully."]);
    // }

}
