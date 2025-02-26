<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;

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

}
