<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations;
 
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SingleResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Services\CoreServices\ModelShowService;

class GeographicalAreaShowService extends ModelShowService
{
     
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    protected function getShowingResource() 
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
    } 
     
    protected function respond()
    {
        $resourceClass = $this->getShowingResource();
        return new $resourceClass($this->model);
    }
}
