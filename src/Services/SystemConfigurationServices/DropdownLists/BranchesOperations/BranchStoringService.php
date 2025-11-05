<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\BranchStoringRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

class BranchStoringService extends MultiRowStoringService
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
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(BranchStoringRequest::class);
    }
}
