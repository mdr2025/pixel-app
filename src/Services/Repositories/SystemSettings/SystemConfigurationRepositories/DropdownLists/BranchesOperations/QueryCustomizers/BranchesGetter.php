<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers;

use Illuminate\Database\Eloquent\Builder;
use PixelApp\Models\ModelConfigs\DropdownLists\BranchesOperations\BranchConfig;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use Spatie\QueryBuilder\QueryBuilder;

class BranchesGetter extends QueryCustomizer
{

    public function customizeQuery(Builder | QueryBuilder $query) : self
    {
        $this->setQuery($query);
        return $this->setAllowedFilters()
                    ->setQueryScopes()
                    ->eagerLoadRelations();
    }
 
 
    protected function eagerLoadRelations() : self
    {
        $this->getQuery()->with(BranchConfig::getRelations())->without(['children']);
        return $this;
    }

    protected function setQueryScopes() : self
    {
        $this->getQuery()->scopes(['datesFiltering' , 'customOrdering']);
        return $this;
    }

    protected function getSpatieAllowedFilters()  :array
    {
        return BranchConfig::getFilters();
    }

    protected function setAllowedFilters() : self
    {
        if($this->getQuery() instanceof QueryBuilder)
        {
            $this->getQuery()
                 ->allowedFilters( $this->getSpatieAllowedFilters() );
        }

        return $this;
    }

    public function getResult(): mixed
    {
        return  $this->getQuery()->paginate((int) (request()->pageSize ?? 10));
                            
    }
}