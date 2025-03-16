<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc;

use ExpImpManagement\ExportersManagement\Exporter\Exporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use ExpImpManagement\ExportersManagement\ExportingBaseService\ExportingBaseService;

abstract class DropDownListExportingService extends ExportingBaseService
{
    protected function initExporter($exporterType) : Exporter
    {
        return match($exporterType)
        {
            "pdf" => $this->initPdfExporter(),
            default => $this->initCSVExporter()
        };
    }

    abstract protected function initPdfExporter() : PDFExporter; 

    abstract protected function initCSVExporter() : CSVExporter;
}