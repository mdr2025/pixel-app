<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ImportingFunc;
 
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentImportingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVImporter;

class DepartmentsImporter extends DropDownListCSVImporter
{
    protected function getFormatFileName() : string
    {
        return "Departments-Template";
    }

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }

    public function getDataValidationRequestFormClassForSelfConstructing() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(DepartmentImportingRequest::class);
    }

    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        
        $fileName = $this->getFormatFileName();
        $fileName = $this->handleTenantFileName($fileName);
        
        return new DepartmentsImportableFileFormatFactory($fileName);
    }

}