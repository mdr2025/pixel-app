<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentUpdatingRequest;

class DepartmentUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return 'Failed to update the record.';
    }
    protected function getModelUpdatingSuccessMessage(): string
    {
        return 'The record has been updated successfully.';
    }

    protected function onBeforeDbCommit(): void
    {
        if ($this->Model->is_default == 1)
        {
            throw new Exception("You cannot update this department.");
        }
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(DepartmentUpdatingRequest::class);
    }

}
