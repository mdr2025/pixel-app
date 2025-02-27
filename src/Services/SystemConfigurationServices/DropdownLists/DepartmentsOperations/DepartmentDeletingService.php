<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;
use PixelApp\Models\SystemConfigurationModels\Department;

class DepartmentDeletingService extends DeletingService
{
    protected function getModelDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Department";
    }

    protected function getModelDeletingSuccessMessage(): string
    {
        return "The Department Has Been Deleted Successfully !";
    }
    protected function AuthorizeByPolicy(): bool
    {
        return BasePolicy::check('delete', Department::class);
    }
}
