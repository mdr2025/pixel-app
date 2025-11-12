<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\Traits;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder; 

trait ExporterQueryBuilderCustomization
{
     
    protected function eagerLoadRelations($builder) : void
    {
        $builder->with("department" , "role");
    }

    protected function applyUserTypeScope($builder) : void
    {
        $builder->scopes('activeUsers');
    }
    protected function initQueryBuilder() : Builder | DatabaseQueryBuilder | QueryBuilder
    {
        $builder = parent::initQueryBuilder();
        $this->applyUserTypeScope($builder);
        $this->eagerLoadRelations($builder); 
        return $builder;
    }

}