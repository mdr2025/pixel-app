<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\NormalCompanySettingsControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyDataResettingService\NormalCompanyDataResettingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Http\Requests\CompanyAccountRequests\ResetCompanyDataRequest;
use Illuminate\Support\Facades\Auth;

class NormalCompanyDataResettingController extends Controller
{ 
   
    public function resetData(ResetCompanyDataRequest $request) : JsonResponse
    { 
        return $this->surroundWithTransaction(
                    function() : JsonResponse
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(NormalCompanyDataResettingService::class);
                        return (new $service)->resetData();
                    },
                    'Reset Data',
                        [
                            'user_id' => Auth::id(),
                            'request' => $request->all(),
                        ]
                );
    }
  
  
}
