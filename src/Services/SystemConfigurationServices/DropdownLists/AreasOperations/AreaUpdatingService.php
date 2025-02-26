<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Areas\UpdatingAreaRequest;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;


class AreaUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Area !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "The Area Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingAreaRequest::class;
    }


}
