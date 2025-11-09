<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use ExpImpManagement\ImportersManagement\ImporterTypes\CSVImporter\CSVImporter;
use ExpImpManagement\ImportersManagement\ImporterTypes\CSVImporter\Traits\CSVImporterSelfConstructing;
use ExpImpManagement\ImportersManagement\Interfaces\SelfConstructableCSVImporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;
use PixelApp\Traits\ExportedFileNameGeneratingTrait;

abstract class DropDownListCSVImporter 
         extends CSVImporter 
         implements SelfConstructableCSVImporter
{
    use CSVImporterSelfConstructing , ExportedFileNameGeneratingTrait;

    abstract protected function getFormatFileName() : string;
 
    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        
        $fileName = $this->getFormatFileName();
        $fileName = $this->handleTenantFileName($fileName);
        
        return new DropDownListCSVFileFormatFactory($fileName);
    }
}