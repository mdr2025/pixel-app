<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations;

use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\GeographicalAreas\GeographicalAreasListResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea; 
use PixelApp\Services\CoreServices\ModelListingService;

class GeographicalAreasListingingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(GeographicalAreasListResource::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name', 'city_id']);
    }

    protected function getSelectedColumns() : array
    {
        return ['id', 'name', 'city_id'];
    }
    protected function setCustomScopes() : void
    {
        $this->query->scopes('active');
    }

    protected function respond($data)
    {
        $resourceClass = $this->getListingResource();
        return $resourceClass::collection($data); 
    }
   
}
