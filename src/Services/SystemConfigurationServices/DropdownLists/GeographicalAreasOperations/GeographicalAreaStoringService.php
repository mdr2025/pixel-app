<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\GeographicalAreas\StoringGeographicalAreaRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;

class GeographicalAreaStoringService extends MultiRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Area !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "The Area Has Been Created Successfully !";
    }

    protected function getModelClass(): string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(StoringGeographicalAreaRequest::class);
    }

}
