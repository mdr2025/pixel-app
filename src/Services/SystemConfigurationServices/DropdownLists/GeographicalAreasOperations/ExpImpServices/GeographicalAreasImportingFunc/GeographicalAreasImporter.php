<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasImportingFunc;

use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVImporter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\GeographicalAreas\GeographicalAreasImportingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;

class GeographicalAreasImporter extends DropDownListCSVImporter
{
    protected function getFormatFileName() : string
    {
        return "areas";
    }

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    public function getDataValidationRequestFormClassForSelfConstructing() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(GeographicalAreasImportingRequest::class);
    }

    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        return new GeographicalAreaImportableFileFormatFactory($this->getFormatFileName());
    }

}