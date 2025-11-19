<?php

namespace PixelApp\Services\CompanyAccountServices\BaseServices\CompanyDataResettingService;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\CompanyAccountRequests\ResetCompanyDataRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Services\Traits\GeneralValidationMethods;
use Throwable;

abstract class CompanyDataResettingBaseService
{

    use GeneralValidationMethods;

    abstract protected function dispatchDataResettingJob() : void;

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ResetCompanyDataRequest::class);
    }

    public function resetData() : JsonResponse
    { 
        try
        {
            //if no exception is thrown the execution will continue
            $this->initValidator()->validateRequest()->setRequestData();

            $confirmName = $this->data["text"];
            
            if ($confirmName !== "DELETE") 
            {
                return Response::error(["You didn't confirm the deleting operation .... Please write DELETE work to text field to confirm the deleting operation !"]);
            }

            $this->dispatchDataResettingJob();
 
            return Response::success([], ["Reset Data Process Will Be Finished Within Few Minutes, PixelAppreciate Your Patience."]);
        
        }catch(Throwable $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }
  

}