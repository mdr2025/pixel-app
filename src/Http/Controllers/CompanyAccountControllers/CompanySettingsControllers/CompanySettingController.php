<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\CompanyAccountRequests\ResetCompanyDataRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Jobs\ResetCompanyDataJob;
use PixelApp\Services\Traits\GeneralValidationMethods;
use Throwable;

class CompanySettingController extends Controller
{ 
    use GeneralValidationMethods;

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ResetCompanyDataRequest::class);
    }

    protected function validateDataResetingRequest() : void
    {
        $this->initValidator()->validateRequest();
    }

    public function resetData() : JsonResponse
    { 
        try
        {
            //if no exception is thrown the execution will continue
            $this->validateDataResetingRequest();

            $confirmName = request()->input('action');
            
            if ($confirmName !== "DELETE") 
            {
                return Response::error(["You Have The Word Typed (DELETE) Wrongly, Please Type it Correctly."]);
            }
            ResetCompanyDataJob::dispatch();

            
            return Response::success([], ["Reset Data Process Will Be Finished Within Few Minutes, PixelAppreciate Your Patience."]);
        }catch(Throwable $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }
  
  
}
