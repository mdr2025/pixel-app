<?php

namespace PixelApp\Http\Resources\UserManagementResources;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;

class UsersListResource extends JsonResource
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
            "name" => $this->name,
            'logo' => $this->whenLoaded('profile', fn() => (new UserProfileResource($this->profile))?->toArray($request)["logo"] ?? null),
        ];
    }
}