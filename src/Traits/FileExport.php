<?php

namespace PixelApp\Traits;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use Spatie\QueryBuilder\QueryBuilder;

trait FileExport
{
    /**
     * Export data to a CSV file.
     *
     * @param string $model
     * @param string $importableFormatFactory
     * @param string $fileName
     */
    public static function export($model, $importableFormatFactory, string $fileName)
    {
        return (new CSVExporter($model))
            ->useQueryBuilderClass(QueryBuilder::class)
            ->useImportableFormatFileFactory(
                new $importableFormatFactory(self::generateFileName($fileName))
            )
            ->export(self::generateFileName($fileName));
    }

    /**
     * Download file format.
     *
     * @param string $importableFormatFactory
     * @param string $fileName
     */
    public static function downloadFileFormat($importableFormatFactory, $fileName)
    {
        return (new $importableFormatFactory(self::generateFileName($fileName)))->downloadFormat();
    }

    /**
     * Generate a file name with tenant abbreviation or name prefix.
     *
     * @param string $fileName
     * @return string
     */
    private static function generateFileName(string $fileName): string
    {
        return tenant()->abbreviation ?? (
            str_word_count(tenant()->name) > 1
            ? strtok(tenant()->name, ' ')
            : tenant()->name
        ) . $fileName;
    }
}
