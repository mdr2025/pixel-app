<?php
 
namespace PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\PixelModelManager;

class AclResoursce extends JsonResource
{
    protected function getAllPermissions() : Collection
    {
        return PixelModelManager::getPermissionModelClass()::query()->pluck("name");
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
        $permissions = $this->getAllPermissions();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => $this->getPermissionsResourceClass()::collection($permissions)
        ];
    }
}
