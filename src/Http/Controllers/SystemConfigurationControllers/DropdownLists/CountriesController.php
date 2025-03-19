<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Countries\CountryResource as CountriesCountryResource;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Http\Resources\WorkSector\Countries\CountryResource;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CountriesServices\CountriesListingService;

class CountriesController extends Controller
{

    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CountriesListingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for(Country::class)
        //                     ->allowedFilters(['name'])
        //                     ->customOrdering('id', 'asc')
        //                     ->get(['id','name','code']);
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(CountriesCountryResource::class);
        // return $resourceClass::collection($data); 
    }
}
