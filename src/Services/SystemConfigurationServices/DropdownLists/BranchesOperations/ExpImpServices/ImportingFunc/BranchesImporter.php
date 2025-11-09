<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc;
 
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\BranchImportingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVImporter;

class BranchesImporter extends DropDownListCSVImporter
{
    protected function getFormatFileName() : string
    {
        return "Branch-Template";
    }

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function getDataValidationRequestFormClassForSelfConstructing() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(BranchImportingRequest::class);
    }

    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        $fileName = $this->getFormatFileName();
        $fileName = $this->handleTenantFileName($fileName);
        
        return new BranchesImportableFileFormatFactory($fileName);
    }

}