<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\UserAccountRequests\ResetCompanyDataRequest;
use PixelApp\Jobs\ResetCompanyDataJob;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin\CompanyChangeDefaultAdminClientService;

class UserCompanySettingController extends Controller
{ 
    public function resetData(ResetCompanyDataRequest $request) : JsonResponse
    {
        BasePolicy::check("resetCompanyData", null);
        $confirmName = $request->input('action');
        if ($confirmName !== "DELETE") {
            return Response::error(["You Have The Word Typed (DELETE) Wrongly, Please Type it Correctly."]);
        }
        ResetCompanyDataJob::dispatch();
        return Response::success([], ["Reset Data Process Will Be Finished Within Few Minutes, PixelAppreciate Your Patience."]);
    }
  
  
    public function updateAdminInfo()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyChangeDefaultAdminClientService::class);
        return (new $service())->update();
    }
  
}
