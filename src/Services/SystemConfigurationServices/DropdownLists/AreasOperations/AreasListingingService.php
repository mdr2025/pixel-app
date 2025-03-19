<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;

use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Areas\AreasListResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area; 
use PixelApp\Services\CoreServices\ModelListingService;

class AreasListingingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(AreasListResource::class);
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
