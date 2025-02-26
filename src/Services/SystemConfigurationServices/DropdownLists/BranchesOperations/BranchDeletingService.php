<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;


class BranchDeletingService extends DeletingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Branch";
    }

    protected function getModelDeletingSuccessMessage(): string
    {
        return "The Branch Has Been Deleted Successfully !";
    }
}
