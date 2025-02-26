<?php

namespace PixelApp\Http\Resources\UserManagementResources\ModelResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
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
    // private function appendBranchInfo(array $dataArrayToChange = []): array
    // {
    //     if ($branch = $this->branch )
    //     {
    //         $dataArrayToChange["branch"] = new BranchResource($branch);
    //     }
    //     return $dataArrayToChange;
    // }
    private function appendDepartmentInfo(array $dataArrayToChange = []): array
    {
        if ($department = $this->department)
        {
            $dataArrayToChange["department"] =  new DepartmentResource($department);
        }
        return $dataArrayToChange;
    }

    private function appendRolePermissions(array $dataArrayToChange = []): array
    {
        if ($role = $this->role)
        {
            $dataArrayToChange["role"] = new RoleResource($role);

            $dataArrayToChange["permissions"] = $role->permissions()->pluck("name")->toArray();
        }
        return $dataArrayToChange;
    }

    // private function getUserData() : array
    // {
    //     if(!$this->resource) { return [];}
    //     return [
    //                 "id" => $this->resource->id,
    //                 "email" => $this->resource->email,
    //                 "first_name" => $this->resource->first_name,
    //                 "last_name"  => $this->resource->last_name,
    //                 "name" => $this->resource->name,
    //                 "full_name" => $this->resource->full_name,
    //                 "mobile" => $this->resource->mobile,
    //                 "accepted_at" => $this->resource->accepted_at,
    //                 "employee_id" => $this->resource->employee_id,
    //                 "status" =>   User::UserStatusNames[ $this->resource->status ] ,
    //                 "remember_token" => $this->resource->remember_token,
    //                 "created_at" => $this->resource->created_at,
    //             ];
    // }

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
        // $data = $this->getUserData();
        $data = $this->appendRolePermissions();
        return array_merge($data , parent::toArray($request));
        // $data = $this->appendDepartmentInfo($data);
        //return $this->appendBranchInfo($data);
    }
}
