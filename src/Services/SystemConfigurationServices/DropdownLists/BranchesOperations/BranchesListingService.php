<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Areas\AreasListResource;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch; 
use PixelApp\Services\CoreServices\ModelListingService;

class BranchesListingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name' ]);
    }

    protected function getSelectedColumns() : array
    {
        return ['id', 'name'];
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
