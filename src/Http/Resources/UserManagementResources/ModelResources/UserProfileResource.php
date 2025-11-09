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

    public function getPictureData() : array
    {
        return ["picture" => $this->resource->getPicture() ];
    }
    protected function getNationalityResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(CountryResource::class);
    }

    protected function getNationalityInfo(): array
    {
        $data = [];
        if ($nationality = $this->resource->nationality)
        {
            $resourceClass = $this->getNationalityResourceClass();
            $data["nationality"] = (new $resourceClass( $nationality ) )->toArray($this->request);
            unset($this->resource->nationality);
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
        $data = $this->getNationalityInfo();
        
        
        // need to be handled by child class if it has city relation
        // $data = $this->appendCityInfo($data);

        return array_merge($data, parent::toArray($request));
    }
}
