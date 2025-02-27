<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;
use Exception;
use PixelApp\Models\PixelBaseModel;

class BranchDeletingService extends DeletingService
{

    public function __construct(PixelBaseModel $branch)
    {
        if ($branch->id == 1)
        {
            throw new Exception("Deleting Main Branch is not allowed , One Branch at least has to be existed");
        }
        parent::__construct($branch);
    }
    
    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Branch";
    }

    protected function getModelDeletingSuccessMessage(): string
    {
        return "The Branch Has Been Deleted Successfully !";
    }
}
