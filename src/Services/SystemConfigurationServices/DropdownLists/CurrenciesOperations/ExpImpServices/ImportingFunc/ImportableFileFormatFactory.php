<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVBooleanSelectingColumnInfo;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\DecimalCellValidationSetter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\ListCellValidationSetter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\TextLengthCellValidationSetter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class ImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();


        $columns[] = (new CSVFormatColumnInfoComponent("D" , "Code"))
                                ->setDatabaseFieldName('code')
                               ->setCellDataValidation(new TextLengthCellValidationSetter(255));
                                                                              
        $columns[] = (new CSVFormatColumnInfoComponent("E" , "Symbol"))
                                ->setDatabaseFieldName('symbol')
                               ->setCellDataValidation(new TextLengthCellValidationSetter(255));

        $columns[] = (new CSVFormatColumnInfoComponent("F" , "Native Symbol (optioanl)"))
                                ->setDatabaseFieldName('symbol_native')
                               ->setCellDataValidation(new TextLengthCellValidationSetter(255));

                                                                              
        $columns[] = (new CSVFormatColumnInfoComponent("G" , "Decimal Digits  (optioanl)"))
                                ->setDatabaseFieldName('decimal_digits')
                               ->setCellDataValidation(new DecimalCellValidationSetter());
        
        
        $columns[] = (new CSVBooleanSelectingColumnInfo("H" , "Needs digit rounding ? (optioanl)" , "Yes" , "No"))
                                 ->setDatabaseFieldName('rounding');

        $columns[] = (new CSVFormatColumnInfoComponent("I" , "Curerency plural name (optioanl)"))
                                ->setDatabaseFieldName('name_plural')
                               ->setCellDataValidation(new TextLengthCellValidationSetter(255));

        $columns[] = (new CSVBooleanSelectingColumnInfo("J" , "Is it system main currency ? (optioanl)" , "Yes" , "No"))
                                 ->setDatabaseFieldName('is_main');
                                                                                     
        return $columns;
    }

}