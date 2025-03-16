<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\Tratis;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder; 

trait ExporterQueryBuilderCustomization
{
    
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'first_name', 'last_name', 'name', 'email', 'mobile']);
    }
   
    protected function initQueryBuilder() : Builder | DatabaseQueryBuilder | QueryBuilder
    {
        $builder = parent::initQueryBuilder(); 
        $this->SelectColumns($builder);
        return $builder;
    }

}