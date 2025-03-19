<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;
 
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SingleResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\CoreServices\ModelShowService;

class BranchShowService extends ModelShowService
{
     
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
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
