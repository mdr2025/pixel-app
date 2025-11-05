<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentStoringRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;

class DepartmentStoringService extends MultiRowStoringService
{
    protected function getModelCreatingFailingErrorMessage(): string
    {
        return 'Failed to create the record.';
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return 'The record has been created successfully.';
    }

    protected function getModelClass(): string
    {
        return PixelModelManager::getModelForModelBaseType( Department::class );
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(DepartmentStoringRequest::class);
    }

}
