<?php

namespace PixelApp\Services\CoreServices;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

abstract class ModelIndexingService
{

    protected QueryBuilder $query ;
    abstract protected function getModelClass() : string;
    
    abstract protected function getIndexingResource() : string;
    
    protected function getRequets() : Request
    {
        return request();
    }

    protected function paginateData() 
    {
        return $this->query->paginate($this->getRequets()->pageSize ?? 10);
    }

    protected function setCustomScopes() : void
    {
        $this->query->datesFiltering()->customOrdering();
    }

    abstract protected function setAllowedFilters() : void; 

    abstract protected function eagerLoadRelations() : void;
    
    protected function initSpatieQueryBuilder() : void
    {
        $modelClass = $this->getModelClass();
        $this->query = QueryBuilder::for($modelClass);
    }

    public function index() : JsonResponse
    {
        $this->initSpatieQueryBuilder();    
        $this->eagerLoadRelations();
        $this->setAllowedFilters();
        $this->setCustomScopes();

        $data = $this->paginateData();
         
        $resourceClass = $this->getIndexingResource();
        return Response::success(['list' => new $resourceClass($data)]);

    }

}