<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\GeographicalAreas;

use Illuminate\Http\Resources\Json\JsonResource;

class GeographicalAreasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
              "country_id" => $this->city?->country?->id ?? null,
            ]
        );
    }
}
