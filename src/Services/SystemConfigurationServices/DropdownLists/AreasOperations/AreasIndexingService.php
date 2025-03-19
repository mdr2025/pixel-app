<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Areas\AreasResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Services\CoreServices\ModelIndexingService;

class AreasIndexingService extends ModelIndexingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    protected function getIndexingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(AreasResource::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name', 'city.name', 'city.country.name']);
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with(['city', 'city.country']);
    }

    protected function respond($data)
    {
        $resourceClass = $this->getIndexingResource();
        return Response::success(['list' => new $resourceClass($data)]);
    }
   
}
