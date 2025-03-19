<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department; 
use PixelApp\Services\CoreServices\ModelListingService;

class DepartmentsListingingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    } 
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name']);
    }

    protected function getSelectedColumns() : array
    {
        return ['id', 'name'];
    }
    protected function setCustomScopes() : void
    {
        $this->query->scopes('active');
    }

    protected function getTotalActiveDepartments() : int
    {
        $modelClass = $this->getModelClass();
        return $modelClass::active()->count();
    }

    protected function respond($data)
    {
        $total = $this->getTotalActiveDepartments();
            
        return Response::successList($total, $data); 
    }
   
}
