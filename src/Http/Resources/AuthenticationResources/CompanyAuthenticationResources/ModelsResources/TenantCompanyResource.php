<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources;

use PixelApp\Models\CompanyModule\TenantCompany;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property TenantCompany $resource
 */
class TenantCompanyResource extends JsonResource
{
    protected array $nonDesiredAttrs = [ "country_id" ,"hashed_id" , "updated_at" , "deleted_at" , "data" ];

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

    protected function getRelationsProps(): array
    {
        return array_map(fn($relation) =>
            is_object($relation) && method_exists($relation, 'toArray')
                ? $this->filterProps($relation->toArray())
                : $relation,
            $this->resource->getRelations()
        );
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
