<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\CoreServices\ModelIndexingService;

/**
 * For referance
 */
class BranchesIndexingService extends ModelIndexingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    } 
    protected function respond($data) 
    {
        return Response::success(['list' => $data]);
    }  

    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name','status']);
    }

    protected function eagerLoadRelations() : void
    {
        //to check later
        // $this->query->with(['parent' ]);
    }
   
}
