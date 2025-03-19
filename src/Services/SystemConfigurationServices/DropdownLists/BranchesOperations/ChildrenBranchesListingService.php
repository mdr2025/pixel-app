<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch; 
use PixelApp\Services\CoreServices\ModelListingService;

class ChildrenBranchesListingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }
  
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name','status' ]);
    }

    protected function getData() 
    {
        return $this->query->first();
    }

    protected function setCustomScopes() : void
    {
        $this->query->scopes('datesFiltering');
    }

    protected function setParentIdCondition() : void
    {
        $this->query->whereNull('parent_id');
    }

    protected function initSpatieQueryBuilder(): void
    {
        parent::initSpatieQueryBuilder();
        $this->setParentIdCondition();    
    }

    protected function respond($data)
    {  
        return Response::success( $data->toArray()); 
    }
   
}
