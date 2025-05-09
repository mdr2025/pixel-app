<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Cities\UpdatingCitiesRequest;

class CitiesUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given City !';
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return 'The City Has Been Updated Successfully !';
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdatingCitiesRequest::class);
    }

}
