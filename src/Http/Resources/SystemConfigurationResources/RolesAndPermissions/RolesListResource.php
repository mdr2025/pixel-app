<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions;

use Illuminate\Http\Resources\Json\JsonResource; 

class RolesListResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'activate_button' => $this->activate_button,
            'default' => $this->default,
            'edit_button' => $this->edit_button,
            'delete_button' => $this->delete_button,
            'status' => $this->status,
            'user_count' => $this->user()->count(),
            'permissions_count' => $this->permissions()->count(),
        ];
    }
}
 
