<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Cities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "name" => $this->name,
            "country_id" => $this->country_id
        ];
    }
}
