<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Departments;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!$this->resource) { return [];}

        return [
            "id"=>$this->id,
            "name" => $this->name
        ];
    }
}
