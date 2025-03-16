<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;
 
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SingleResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Services\CoreServices\ModelShowService;

class AreaShowService extends ModelShowService
{
     
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    protected function getShowingResource() 
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
    } 
     
}
