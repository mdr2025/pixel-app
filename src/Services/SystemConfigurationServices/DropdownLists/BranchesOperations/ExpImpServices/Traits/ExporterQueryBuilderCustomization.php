<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\Traits;
 
trait ExporterQueryBuilderCustomization
{
     
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'default']);
    }
    

}