<?php
 
namespace PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions;

use Illuminate\Http\Resources\Json\JsonResource; 
class AclResoursce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => PermissionsResource::collection($this->getAllPermissions())
        ];
    }
}
