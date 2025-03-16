<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices;

use ExpImpManagement\ExportersManagement\Exporter\Exporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use ExpImpManagement\ExportersManagement\ExportingBaseService\ExportingBaseService;
use PixelApp\Models\PixelModelManager;

class SignUpUsersExportingService extends ExportingBaseService
{
    protected function initExporter($exporterType) : Exporter
    {
        return match($exporterType)
        {
            "pdf" => $this->initPdfExporter(),
            default => $this->initCSVExporter()
        };
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
    protected function initPdfExporter() : PDFExporter
    {
        return  new SignUpUsersPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new SignUpUsersCSVExporter();
    }
}