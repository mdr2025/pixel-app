<?php

namespace PixelApp\Http\Resources\UserManagementResources;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;

class UsersListResource extends JsonResource
{
    protected function getUserProfileResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UserProfileResource::class);
    } 

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userProfileResourceClass = $this->getUserProfileResourceClass();
        return [
            "id" => $this->id,
            "name" => $this->name,
            'logo' => $this->whenLoaded('profile', fn() => (new $userProfileResourceClass($this->profile))?->toArray($request)["logo"] ?? null),
        ];
    }
}