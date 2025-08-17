<?php

namespace PixelApp\Http\Resources\UserManagementResources\ModelResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Departments\DepartmentResource;
use PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions\RoleResource;
use PixelApp\Models\CompanyModule\CompanyAccountModels\CompanyAccount;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;

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

    protected function fetchTenantCompany() : ?TenantCompany
    {
        return tenant();
    }

    protected function fetchTenantCompanyLogo() : string
    {
        return $this->fetchTenantCompany()?->getFileFullPathAttrValue('logo') ?? "";
    }

    protected function doesItNeedTenantCompanyLogo() : bool
    {
        $tenant = $this->fetchTenantCompany();

        return (PixelAppBootingManager::isBootingForMonolithTenancyApp() && $tenant != null)
               ||
               (PixelAppBootingManager::isBootingForTenantApp() && $tenant != null);
    }

    protected function fetchCompanyAccountLogo() : string
    {
        $companyAccountClass = PixelModelManager::getModelForModelBaseType(CompanyAccount::class);
        return $companyAccountClass::orderBy("id" , "asc")->first(["logo"])?->logo ?? "";
    }

    protected function doesItNeedCompanyAccountLogo() : bool
    {
        $tenant = $this->fetchTenantCompany();

        return PixelAppBootingManager::isBootingForAdminPanelApp() 
                ||
                PixelAppBootingManager::isBootingForNormalApp()
                ||
                (PixelAppBootingManager::isBootingForMonolithTenancyApp() && $tenant == null);
    }

    protected function appendCompanyLogo(array $dataArrayToChange) : array
    { 
        if($this->doesItNeedCompanyAccountLogo())
        {
            $dataArrayToChange['company_logo'] =  $this->fetchCompanyAccountLogo();

        }elseif( $this->doesItNeedTenantCompanyLogo())
        {
            $dataArrayToChange['company_logo'] = $this->fetchTenantCompanyLogo();
        }

        return $dataArrayToChange;
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
        $data = $this->appendCompanyLogo($data);
        
        return array_merge($data , parent::toArray($request));

        //return $this->appendBranchInfo($data);
    }
}
