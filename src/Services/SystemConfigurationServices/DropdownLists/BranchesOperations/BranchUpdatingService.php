<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\UpdatingBranchRequest;

class BranchUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Branch !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "The Branch Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingBranchRequest::class;
    }
}
