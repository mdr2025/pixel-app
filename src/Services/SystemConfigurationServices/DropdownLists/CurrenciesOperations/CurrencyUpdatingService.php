<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations;

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Currencies\UpdatingCurrencyRequest;

class CurrencyUpdatingService extends UpdatingService
{

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Currency !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "The Currency Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdatingCurrencyRequest::class);
    }


}
