<?php

namespace PixelApp\Services\CoreServices;
 
use Spatie\QueryBuilder\QueryBuilder;

abstract class ModelListingService
{

    protected QueryBuilder $query ;
    abstract protected function getModelClass() : string;
    
    abstract protected function respond($data) ;
     
    abstract protected function setAllowedFilters() : void; 
    
    protected function getSelectedColumns() : array
    {
        return ['*'];
    }

    protected function getData() 
    {
        $columns = $this->getSelectedColumns();
        return $this->query->get($columns);
    }

    protected function setCustomScopes() : void
    {
        $this->query->customOrdering('created_at', 'desc');
    } 
 
    protected function initSpatieQueryBuilder() : void
    {
        $modelClass = $this->getModelClass();
        $this->query = QueryBuilder::for($modelClass);
    }

    public function list() 
    {  
        $this->initSpatieQueryBuilder();  
        $this->setAllowedFilters();
        $this->setCustomScopes();

        $data = $this->getData();
        $this->respond($data);
         
    }

}