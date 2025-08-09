<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ImportingFunc;
 
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Currencies\CurrencyImportingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVImporter;

class CurrenciesImporter extends DropDownListCSVImporter
{
    protected function getFormatFileName() : string
    {
        return "currencies";
    }

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Currency::class);
    }

    public function getDataValidationRequestFormClassForSelfConstructing() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(CurrencyImportingRequest::class);
    }

    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        return new ImportableFileFormatFactory($this->getFormatFileName());
    }

}