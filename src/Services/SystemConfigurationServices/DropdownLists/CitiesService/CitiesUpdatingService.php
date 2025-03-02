<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesService;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Cities\UpdatingCitiesRequest;

class CitiesUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given CitiesUpdatingService !';
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return 'The CitiesUpdatingService Has Been Updated Successfully !';
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdatingCitiesRequest::class);
    }

}
