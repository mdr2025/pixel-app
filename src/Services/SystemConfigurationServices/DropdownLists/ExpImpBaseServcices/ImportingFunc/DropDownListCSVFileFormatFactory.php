<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVBooleanSelectingColumnInfo;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\DecimalCellValidationSetter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\ListCellValidationSetter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\TextLengthCellValidationSetter;

class DropDownListCSVFileFormatFactory extends CSVImportableFileFormatFactory
{ 
    protected function getColumnFormatInfoCompoenents() : array
    {
        return [
            (new CSVFormatColumnInfoComponent("A" , 'Id'))->setDatabaseFieldName('id')
                                                         ->setCellDataValidation(new DecimalCellValidationSetter),
            (new CSVFormatColumnInfoComponent("B" , 'Name'))->setDatabaseFieldName('name')
                                                            ->setCellDataValidation(new TextLengthCellValidationSetter(255)), 
            (new CSVBooleanSelectingColumnInfo("C" , 'Status' , "Yes" , "No"))->setDatabaseFieldName('status')
                                                              ->setCellDataValidation(new ListCellValidationSetter(["0" , "1"]))
            
        ];
    }
}