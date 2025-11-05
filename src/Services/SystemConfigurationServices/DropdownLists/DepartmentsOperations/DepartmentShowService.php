<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SingleResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\CoreServices\ModelShowService;

/**
 * For referance
 */
class DepartmentShowService extends ModelShowService
{
     
    public function __construct(int $key, string $fetchingColumn = 'id')
    {
        BasePolicy::check('read', Department::class);

        parent::__construct($key , $fetchingColumn);
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
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
