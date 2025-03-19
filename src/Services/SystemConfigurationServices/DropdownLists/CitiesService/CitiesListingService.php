<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices;

use PixelApp\Http\Resources\PixelHttpResourceManager; 
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Cities\CityResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City; 
use PixelApp\Services\CoreServices\ModelListingService;

class CitiesListingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(City::class);
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CityResource::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name', 'country_id']);
    }

    protected function getSelectedColumns() : array
    {
        return['id', 'name', 'country_id'];
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
