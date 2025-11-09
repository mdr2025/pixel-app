<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc;

use ExpImpManagement\ExportersManagement\Exporter\Traits\ExporterSelfConstructing;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\Interfaces\ExportsCSVImportableData;
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructableExporter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Traits\ExporterQueryBuilderCustomization;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Traits\ExporterQueryAllowedFiltersSetting;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PixelApp\Traits\ExportedFileNameGeneratingTrait;

abstract class DropDownListCSVExporter 
         extends CSVExporter 
         implements SelfConstructableExporter , ExportsCSVImportableData
{
    use  ExporterSelfConstructing ,
         ExporterQueryBuilderCustomization ,
         ExporterQueryAllowedFiltersSetting,
         ExportedFileNameGeneratingTrait;
   
    abstract protected function getFormatFileName() : string;

    protected function processFileName(string $fileName) : string
    {
        return $this::handleTenantFileName($fileName);
    }

    public function getCSVImportableFileFormatFactory() : CSVImportableFileFormatFactory
    {
        return new DropDownListCSVFileFormatFactory($this->getFormatFileName());
    }

    public function exportUsingInternalFormatName(bool $handleTenantFileNaming = true) : JsonResponse | StreamedResponse
    {
        $fileName = $this->getFormatFileName();
        
        if($handleTenantFileNaming)
        {
            $fileName = $this->processFileName($fileName);
        }
        
        return $this->export($fileName);
    }
}