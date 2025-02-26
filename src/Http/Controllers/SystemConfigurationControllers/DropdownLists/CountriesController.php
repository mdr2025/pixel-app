<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Countries\CountryResource as CountriesCountryResource;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Http\Resources\WorkSector\Countries\CountryResource;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

class CountriesController extends Controller
{

    function list()
    {
        $data = QueryBuilder::for(Country::class)
                            ->allowedFilters(['name'])
                            ->customOrdering('id', 'asc')
                            ->get(['id','name','code']);
        return CountriesCountryResource::collection($data);
    }
}
