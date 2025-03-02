<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\UpdatingBranchRequest;
use PixelApp\Models\PixelBaseModel;

class BranchUpdatingService extends UpdatingService
{

    public function __construct(PixelBaseModel $branch)
    {
        if (!is_null(request()->status) && $branch->id == 1)
        {
            throw new Exception("Disabling Main Branch is not allowed , One Branch at least has to be existed");
        }
        parent::__construct($branch);
    }
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
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdatingBranchRequest::class);
    }
    protected function doBeforeSuccessResponding(): void
    {
        $model = $this->Model ;
        if ($model->id == 1 || $model->type == 'main'){
            $model->update([
                'parent_id' => null
            ]);
        }
    }
}
