<?php
 
namespace PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;

class AclResoursce extends JsonResource
{
    protected function getAllPermissions() 
    {
        /**
         * @todo later
         */
    }
    protected function getPermissionsResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(PermissionsResource::class);
    }
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
            'permissions' => $this->getPermissionsResourceClass()::collection($this->getAllPermissions())
        ];
    }
}
