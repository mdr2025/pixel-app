<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\UpdatingDepartmentRequest;

class DepartmentUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Department !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "The Department Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingDepartmentRequest::class;
    }

}
