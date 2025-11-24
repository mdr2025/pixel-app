<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\GeographicalAreas;

use Illuminate\Http\Resources\Json\JsonResource;

class GeographicalAreasListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }
}
