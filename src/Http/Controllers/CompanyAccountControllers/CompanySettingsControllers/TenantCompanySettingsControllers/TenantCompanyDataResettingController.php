<?php

namespace PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\TenantCompanySettingsControllers;

use Illuminate\Support\Facades\Auth;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Requests\CompanyAccountRequests\ResetCompanyDataRequest;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyDataResettingService\TenantCompanyDataResettingService;
use PixelApp\Services\PixelServiceManager;

class TenantCompanyDataResettingController extends Controller
{ 
   
    public function resetData(ResetCompanyDataRequest $request) : JsonResponse
    { 
        return $this->surroundWithTransaction(
                    function() : JsonResponse
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(TenantCompanyDataResettingService::class);
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
