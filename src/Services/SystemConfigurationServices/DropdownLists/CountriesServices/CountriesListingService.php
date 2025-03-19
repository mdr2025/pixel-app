<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CountriesServices;

use PixelApp\Http\Resources\PixelHttpResourceManager; 
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Countries\CountryResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country; 
use PixelApp\Services\CoreServices\ModelListingService;

class CountriesListingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Country::class);
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CountryResource::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name']);
    }

    protected function getSelectedColumns() : array
    {
        return ['id','name','code'];
    }

    protected function setCustomScopes() : void
    {
        $this->query->customOrdering('id', 'asc');
    }

    protected function respond($data)
    {
        $resourceClass = $this->getListingResource();
        return $resourceClass::collection($data); 
    }
   
}
