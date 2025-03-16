<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs;

use ExpImpManagement\ExportersManagement\Exporter\Traits\ExporterSelfConstructing;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructableExporter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\Tratis\ExporterQueryAllowedFiltersSetting;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\Tratis\ExporterQueryBuilderCustomization;

abstract class UserCSVExporter extends CSVExporter implements SelfConstructableExporter
{
    use  ExporterSelfConstructing , ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;

    abstract protected function getUserCSVFileFormatFactory() : CSVImportableFileFormatFactory;
    
    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getUserModelClass();
    }
   
    protected function useDefaultDataProcessor() : void
    {
        $fileFormatFactory = $this->getUserCSVFileFormatFactory();
        $this->useImportableFormatFileFactory($fileFormatFactory);
        parent::useDefaultDataProcessor();
    }
}