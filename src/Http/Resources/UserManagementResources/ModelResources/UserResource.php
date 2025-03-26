<?php

namespace PixelApp\Http\Resources\UserManagementResources\ModelResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Departments\DepartmentResource;
use PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions\RoleResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    protected Request $request;

    /**
     * @todo later
     */
    // protected function appendBranchInfo(array $dataArrayToChange = []): array
    // {
    //     if ($branch = $this->branch )
    //     {
    //         $dataArrayToChange["branch"] = new BranchResource($branch);
    //     }
    //     return $dataArrayToChange;
    // }
    
    protected function getDepartmentResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(DepartmentResource::class);
    }

    protected function appendDepartmentInfo(array $dataArrayToChange = []): array
    {
        if ($department = $this->department)
        {
            $resourceClass = $this->getDepartmentResourceClass();
            $dataArrayToChange["department"] =  new $resourceClass($department);
        }
        return $dataArrayToChange;
    }

    protected function getRoleResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(RoleResource::class);
    }

    protected function appendRolePermissions(array $dataArrayToChange = []): array
    {
        if ($role = $this->role)
        {
            $resourceClass = $this->getRoleResourceClass();
            $dataArrayToChange["role"] = new $resourceClass($role);

            $dataArrayToChange["permissions"] = $role->permissions()->pluck("name")->toArray();
        }
        return $dataArrayToChange;
    }
  
    protected function setRequest(Request $request) : void
    {
        $this->request = $request;
    }
    /**
     * @param $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $this->setRequest($request); 
        $data = $this->appendRolePermissions();
        $data = $this->appendDepartmentInfo($data);
        return array_merge($data , parent::toArray($request));

        //return $this->appendBranchInfo($data);
    }
}
