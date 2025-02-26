<?php

namespace PixelApp\Http\Resources\UserAccountResources;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;

class UserProfileSpecificDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mainData = [
            'id' => $this->id,
            'hashed_id' => $this->hashed_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mobile' => $this->mobile,
            'profile' => new UserProfileResource($this->profile)
        ];
        return $mainData;
    }
}
