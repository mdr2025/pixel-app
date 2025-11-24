<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\GeographicalAreas\GeographicalAreasResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Services\CoreServices\ModelIndexingService;

class GeographicalAreasIndexingService extends ModelIndexingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    protected function getIndexingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(GeographicalAreasResource::class);
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
