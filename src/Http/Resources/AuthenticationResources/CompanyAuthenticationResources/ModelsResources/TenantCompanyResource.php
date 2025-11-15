<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources;

use Illuminate\Http\Request;
use PixelApp\Models\CompanyModule\TenantCompany;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Countries\CountryResource;

/**
 * @property TenantCompany $resource
 */
class TenantCompanyResource extends JsonResource
{
    protected array $nonDesiredAttrs = [ "hashed_id"  , "deleted_at" , "data" ];

    public function nonDesiredColumnsFilteringCallback(mixed $value, string|int $key): bool
    { 
        /**
         * Must Not be in nonDesiredAttrs array
         * &&
         * Must not start with tenancy word
         */
        return !Str::startsWith($key , "tenancy_" ) && !in_array($key , $this->nonDesiredAttrs) ;
         
    }

    protected function filterProps(array $props) : array
    {
        return Arr::where($props , [$this , 'nonDesiredColumnsFilteringCallback']);
    }
 
    protected function getRequest() : Request
    {
        return request();
    }

    protected function getcountryresource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CountryResource::class);
    }

    protected function getDefaultAdminResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(DefaultAdminResource::class);
    }
 
    protected function getRelationCustomResourceClass(string $relation) : ?string
    {
        return match($relation)
               {
                    "defaultAdmin" => $this->getDefaultAdminResourceClass(),
                    "country" => $this->getcountryresource(),
                    default => null
               };
    }
    
    protected function getRelationsProps(): array
    {
        $data = [];
        $request = $this->getRequest();

        foreach($this->resource->getRelations() as $relationName => $relationObjects)
        {
            if($resourceClass = $this->getRelationCustomResourceClass($relationName))
            {
                $data[$relationName] = (new $resourceClass( $relationObjects ))->toArray($request);
                continue;
            }

            if(is_object($relationObjects) && method_exists($relationObjects, 'toArray'))
            {
                $data[$relationName] =  $this->filterProps($relationObjects->toArray($request));
                continue;

            } 

            $data[$relationName] = $relationObjects; 
        }

        return $data;
    }

    protected function getTenantCompanyProps() : array 
    {
        return $this->filterProps( $this->resource->attributesToArray() );
    }

    protected function allAttrExceptNonDesired()  :array
    {
        $data["tenant"] = $this->getTenantCompanyProps();
        $data["relations"] = $this->getRelationsProps();
        return $data;
    }

    protected function loadCountryData(): void
    {
        $this->resource->load("country");
    }
    protected function loadDefaultAdmin(): void
    {
        $this->resource->load(["defaultAdmin"]);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->loadCountryData();
        $this->loadDefaultAdmin();
        return $this->allAttrExceptNonDesired();
    }
}
