<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Tratis;
 
trait ExporterQueryAllowedFiltersSetting
{ 
    protected function getDropDownListAllowedFilters() : array
    {
        return  [
                    'name',
                    'code',
                    'symbol',
                    'symbol_native',
                    'status'
                ];
    }   
}