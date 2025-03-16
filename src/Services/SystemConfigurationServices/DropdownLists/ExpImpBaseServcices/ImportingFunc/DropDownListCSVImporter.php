<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use ExpImpManagement\ImportersManagement\ImporterTypes\CSVImporter\CSVImporter;
use ExpImpManagement\ImportersManagement\ImporterTypes\CSVImporter\Traits\CSVImporterSelfConstructing;
use ExpImpManagement\ImportersManagement\Interfaces\SelfConstructableCSVImporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

abstract class DropDownListCSVImporter extends CSVImporter implements SelfConstructableCSVImporter
{
    use CSVImporterSelfConstructing;

    abstract protected function getFormatFileName() : string;
 
    public function getImportableTemplateFactoryForSelfConstructing() : CSVImportableFileFormatFactory
    {
        return new DropDownListCSVFileFormatFactory($this->getFormatFileName());
    }
}