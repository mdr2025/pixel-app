<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Cities\StoringCityRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;

class CitiesStoringService extends MultiRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given Cities !';
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return 'The Cities Has Been Created Successfully !';
    }

    protected function getModelClass(): string
    {
        return PixelModelManager::getModelForModelBaseType( City::class );
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(StoringCityRequest::class);
    }

}
