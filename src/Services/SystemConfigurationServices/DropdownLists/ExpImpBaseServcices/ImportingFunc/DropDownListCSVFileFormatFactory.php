<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;

class DropDownListCSVFileFormatFactory extends CSVImportableFileFormatFactory
{ 
    protected function getColumnFormatInfoCompoenents() : array
    {
        return [
            (new CSVFormatColumnInfoComponent("A" , 'Id'))->setDatabaseFieldName('id'),
            (new CSVFormatColumnInfoComponent("B" , 'Name'))->setDatabaseFieldName('name'), 
            (new CSVFormatColumnInfoComponent("C" , 'Status'))->setDatabaseFieldName('status')
            
        ];
    }
}