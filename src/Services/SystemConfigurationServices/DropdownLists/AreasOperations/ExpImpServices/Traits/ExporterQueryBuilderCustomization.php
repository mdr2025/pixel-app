<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\Tratis;
 
trait ExporterQueryBuilderCustomization
{
     
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'city_id']);
    }
    

}