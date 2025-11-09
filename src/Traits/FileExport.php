<?php

namespace PixelApp\Traits;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use Spatie\QueryBuilder\QueryBuilder;

trait FileExport
{
    use ExportedFileNameGeneratingTrait;

    /**
     * Export data to a CSV file.
     *
     * @param string $model
     * @param string $importableFormatFactory
     * @param string $fileName
     */
    public static function export($model, $importableFormatFactory, string $fileName)
    {
        $fileName = self::handleTenantFileName($fileName);
        $fileFormatFactory = new $importableFormatFactory($fileName);
        
        /**
         * @var CSVImporter $csvImporter
         */
        $csvImporter = (new CSVExporter($model))->useQueryBuilderClass(QueryBuilder::class);

        return $csvImporter->useImportableFormatFileFactory($fileFormatFactory )
                           ->export(self::handleTenantFileName($fileName));
    }

    /**
     * Download file format.
     *
     * @param string $importableFormatFactory
     * @param string $fileName
     */
    public static function downloadFileFormat($importableFormatFactory, $fileName)
    {
        return (new $importableFormatFactory(self::handleTenantFileName($fileName)))->downloadFormat();
    }
}
