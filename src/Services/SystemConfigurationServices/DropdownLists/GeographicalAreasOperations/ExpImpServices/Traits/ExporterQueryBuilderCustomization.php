<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\Traits;
 
trait ExporterQueryBuilderCustomization
{
     
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'city_id']);
    }
    

}