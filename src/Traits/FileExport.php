<?php

namespace PixelApp\Traits;

use Exception;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
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
    public function export(string $model, string $importableFormatFactory, string $fileName)
    {
        $this->validateImportableFileFactory($importableFormatFactory);

        $fileName = $this->handleTenantFileName($fileName);
        $fileFormatFactory = new $importableFormatFactory($fileName);
        
        return (new CSVExporter($model))->useImportableFormatFileFactory( $fileFormatFactory )
                                                ->useQueryBuilderClass(QueryBuilder::class)
                                                ->export($fileName);                   
    }

    /**
     * Download file format.
     *
     * @param string $importableFormatFactory
     * @param string $fileName
     */
    public function downloadFileFormat($importableFormatFactory, $fileName)
    {
        $fileName = $this->handleTenantFileName($fileName);
        
        return (new $importableFormatFactory($fileName))->downloadFormat();
    }

    protected function validateImportableFileFactory(string $factoryClass) : void
    {
        if(!is_subclass_of($factoryClass , CSVImportableFileFormatFactory::class))
        {
            throw new Exception("The class " . $factoryClass . " Must be a child type of " . CSVImportableFileFormatFactory::class); 
        }
    }
}
