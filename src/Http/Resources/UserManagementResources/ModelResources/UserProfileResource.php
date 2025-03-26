<?php

namespace PixelApp\Http\Resources\UserManagementResources\ModelResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Cities\CityResource;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Countries\CountryResource;

class UserProfileResource extends JsonResource
{
    protected Request $request;

    protected function getCityResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CityResource::class);
    }

    protected function appendCityInfo(array $dataArrayToChange = []): array
    {
        if ($city = $this->resource->city)
        {
            $resourceClass = $this->getCityResourceClass();
            $dataArrayToChange["city"] =  (new $resourceClass($city))->toArray($this->request); ;
            unset($this->resource->city);
        }
        return $dataArrayToChange;
    }

    protected function getCountryResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CountryResource::class);
    }

    protected function getCountryInfo(): array
    {
        $data = [];
        if ($country = $this->resource->country)
        {
            $resourceClass = $this->getCountryResourceClass();
            $data["country"] = (new $resourceClass( $country ) )->toArray($this->request);;
            unset($this->resource->country);
        }

        return $data ;
    }

    protected function setRequest(Request $request) : void
    {
        $this->request = $request;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        if(!$this->resource) { return [];}

        $this->setRequest($request);
        $data = $this->getCountryInfo();
        
        
        // need to be handled by child class if it has city relation
        // $data = $this->appendCityInfo($data);

        return array_merge($data, parent::toArray($request));
    }
}
