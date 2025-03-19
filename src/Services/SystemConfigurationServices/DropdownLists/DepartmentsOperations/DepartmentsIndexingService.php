<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\CoreServices\ModelIndexingService;

class DepartmentsIndexingService extends ModelIndexingService
{ 
    public function __construct()
    {
        BasePolicy::check('read', Department::class);
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(["name", "status"]);
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with(['parent' ]);
    }

    protected function respond($data)
    { 
        return Response::success(['list' => $data]); 
    }
   
}
