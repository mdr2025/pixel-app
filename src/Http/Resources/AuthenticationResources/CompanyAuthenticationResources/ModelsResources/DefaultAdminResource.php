<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class DefaultAdminResource extends JsonResource
{

    /**
     * @param $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        if(!$this->resource) { return [];}
        return [
            "id" => $this->resource->id,
            "email" => $this->resource->email,
            "first_name" => $this->resource->first_name,
            "last_name"  => $this->resource->last_name,
            "name" => $this->resource->name,
            "mobile" => $this->resource->mobile,
        ];
    }
}
