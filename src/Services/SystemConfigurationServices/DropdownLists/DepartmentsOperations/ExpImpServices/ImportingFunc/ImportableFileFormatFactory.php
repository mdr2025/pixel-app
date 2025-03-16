<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\DecimalCellValidationSetter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\ListCellValidationSetter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class ImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();
        $columns[] = (new CSVFormatColumnInfoComponent("D" , "Default Branch"))->setDatabaseFieldName('default')
                                                                              ->setCellDataValidation(new ListCellValidationSetter(["0" , "1"]));
        $columns[] = (new CSVFormatColumnInfoComponent("E" , "Parent Department Id"))->setDatabaseFieldName('parent_id')
                                                                                     ->setCellDataValidation(new DecimalCellValidationSetter());
        return $columns;
    }

}