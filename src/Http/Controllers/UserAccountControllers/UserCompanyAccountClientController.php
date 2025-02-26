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

class UserCompanyAccountClientController extends Controller
{

    public function companyProfile() : JsonResponse
    {
        return (new CompanyProfileGettingClientService())->getResponse();
    }
 
    
    /**
     * @throws Exception
     */
    public function updateCompanyProfile(): JsonResponse
    {
        return (new CompanyProfileUpdatingClientService())->getResponse(); 
    }

    public function updateAdminInfo()
    {
        //for now only
        return (new CompanyChangeDefaultAdminServerService())->update();
    }

    // protected function checkResponse(JsonResponse $response, Request $request): JsonResponse
    // {
    //     if ($response->getStatusCode() == 200) {
    //         $CompanyProfileDataResource = new TenantCompanyProfileResource(tenant());
    //         $data = $response->getData(true);
    //         $response->setData([...$data, "data" => $CompanyProfileDataResource->toArray($request)]);
    //     }
    //     return $response;
    // }

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
