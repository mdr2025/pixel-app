<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\StoringBranchRequest;
use PixelApp\Models\SystemConfigurationModels\Branch;

class BranchStoringService extends MultiRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Branch !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "The Branch Has Been Created Successfully !";
    }

    protected function getModelClass(): string
    {
        return Branch::class;
    }

    protected function getRequestClass(): string
    {
        return StoringBranchRequest::class;
    }
}
