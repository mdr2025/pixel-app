<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\StoringDepartmentRequest;
use PixelApp\Models\SystemConfigurationModels\Department;

class DepartmentStoringService extends MultiRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Department !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "The Department Has Been Created Successfully !";
    }

    protected function getModelClass(): string
    {
        return Department::class;
    }

    protected function getRequestClass(): string
    {
        return StoringDepartmentRequest::class;
    }

}
