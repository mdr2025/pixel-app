<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Areas\UpdatingAreaRequest;

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
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdatingAreaRequest::class);
    }


}
