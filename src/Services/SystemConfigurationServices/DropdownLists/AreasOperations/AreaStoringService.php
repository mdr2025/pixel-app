<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Areas\StoringAreaRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;

class AreaStoringService extends MultiRowStoringService
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
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(StoringAreaRequest::class);
    }

}
