<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CountriesServices\CountriesListingService;

class CountriesController extends Controller
{

    function list()
    {
        return $this->logOnFailureOnly(
                    callback: function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(CountriesListingService::class);
                        return (new $service)->list();
                    },
                    operationName: "Countries Listing Operation",
                    loggingFailingMsg:"Failed to retrieve countries list"
                );
    }
}
