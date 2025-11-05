<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch; 
use PixelApp\Services\CoreServices\ModelListingService;


/**
 * For referance
 */
class SubBranchesListingService extends ModelListingService
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
        $this->query->allowedFilters(['name', 'parent_id']);
    }

    protected function getSelectedColumns() : array
    {
        return ['id', 'name'];
    }

    protected function setCustomScopes() : void
    {
        $this->query->scopes('active');
    }

    protected function setParentIdCondition() : void
    {
        $this->query->whereNull('parent_id');
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with('parent');
    }
    protected function initSpatieQueryBuilder(): void
    {
        parent::initSpatieQueryBuilder();
        $this->eagerLoadRelations();    
    }

    protected function respond($data)
    {   
        $resourceClass = $this->getListingResource();
        return $resourceClass::collection($data);
    }
   
}
