<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\AreasImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\DecimalCellValidationSetter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class AreaImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();
        $columns[] = (new CSVFormatColumnInfoComponent("D" , "City Id"))->setDatabaseFieldName('city_id')
                                                                        ->setCellDataValidation(new DecimalCellValidationSetter());
        return $columns;
    }

}