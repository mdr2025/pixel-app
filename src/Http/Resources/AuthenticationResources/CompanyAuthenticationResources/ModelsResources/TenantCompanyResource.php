<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources;

use PixelAppCore\Models\WorkSector\CompanyModule\TenantCompany;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property TenantCompany $resource
 */
class TenantCompanyResource extends JsonResource
{
    protected array $nonDesiredAttrs = [ "country_id" ,"hashed_id" , "updated_at" , "deleted_at" , "data" ];

    protected function allAttrExceptNonDesired()  :array
    {
        return Arr::where($this->resource->toArray() , function ($value , $key){
            /**
             * Must Not be in nonDesiredAttrs array
             * &&
             * Must not start with tenancy word
             */
            return !Str::startsWith($key , "tenancy_" ) && !in_array($key , $this->nonDesiredAttrs) ;
        });
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
