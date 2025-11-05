<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;

use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\BranchUpdatingRequest;


class BranchUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return 'Failed to update the record.';
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return 'The record has been updated successfully.';
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( BranchUpdatingRequest::class );
    }

    protected function doBeforeDbTransactionCommiting(): void
    {
        $model = $this->Model;
        if ($model->id == 1 || $model->type == 'main')
        {
            $model->update([
                'parent_id' => null
            ]);
        }
    }
}
