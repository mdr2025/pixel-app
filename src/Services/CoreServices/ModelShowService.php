<?php

namespace PixelApp\Services\CoreServices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource; 

abstract class ModelShowService 
{
    
    protected Model $model ;
    
    public function __construct(int $key , string $fetchingColumn = 'id')
    {
        $this->model = $this->fetchModelByColumn($key , $fetchingColumn);
    }

    abstract protected function getModelClass() : string; 

    abstract protected function getShowingResource() ;
    
    protected function fetchModelByColumn(int $key , string $fetchingColumn = 'id') : Model
    {
        $modelClass = $this->getModelClass();
        return $modelClass::where($fetchingColumn , $key)->firstOrFail();
    }

    public function show() : JsonResource
    { 
        $resourceClass = $this->getShowingResource();
        return new $resourceClass($this->model);
    }
}
