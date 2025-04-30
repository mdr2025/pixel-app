<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\CompanyAccountRequests\ResetCompanyDataRequest;
use PixelApp\Jobs\ResetCompanyDataJob;

class CompanySettingController extends Controller
{ 
    public function resetData(ResetCompanyDataRequest $request) : JsonResponse
    { 
        $confirmName = $request->input('action');
        if ($confirmName !== "DELETE") {
            return Response::error(["You Have The Word Typed (DELETE) Wrongly, Please Type it Correctly."]);
        }
        ResetCompanyDataJob::dispatch();
        return Response::success([], ["Reset Data Process Will Be Finished Within Few Minutes, PixelAppreciate Your Patience."]);
    }
  
  
}
