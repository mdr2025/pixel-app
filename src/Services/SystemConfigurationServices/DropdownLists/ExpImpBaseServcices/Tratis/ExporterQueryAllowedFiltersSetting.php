<?php

 
namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis;
 

trait ExporterQueryAllowedFiltersSetting
{
     
    protected function getDropDownListAllowedFilters() : array
    {
        return  [
                    'name',
                    'status'
                ];
    }

    protected function applySpatieAllowedFilters() : void
    {
        $allowedFilters = $this->getDropDownListAllowedFilters();
        $this->setSpatieBuilderAllowedFilters($allowedFilters);
        parent::applySpatieAllowedFilters();
    }
}