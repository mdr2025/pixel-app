<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\Traits;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder; 

trait ExporterQueryBuilderCustomization
{
     
    protected function applySignUpUserScope($builder) : void
    {
        $builder->scopes('signUpUser');
    }
    
    protected function initQueryBuilder() : Builder | DatabaseQueryBuilder | QueryBuilder
    {
        $builder = parent::initQueryBuilder();
        $this->applySignUpUserScope($builder); 
        return $builder;
    }

}