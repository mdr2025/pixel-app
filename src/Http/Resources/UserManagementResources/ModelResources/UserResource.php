<?php

namespace PixelApp\Http\Resources\UserManagementResources\ModelResources;
 
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
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


    protected function getBranchRespourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
    }

    private function handleAccessibleBranchesInfo(array &$dataArrayToChange = []): void
    {
        if (
                $this->resource->accessibleBranches != null 
                &&
                $this->resource->accessibleBranches->isNotEmpty()
            ) 
        {
            $branches = $this->resource->accessibleBranches;
            $dataArrayToChange["accessibleBranches"] = BranchResource::collection($branches);
            
            $this->unsetResouceProp("accessibleBranches");
        }
    }


    /**
     * @todo later
     */
    protected function appendBranchInfo(array &$dataArrayToChange = []): void
    {
        if ($branch = $this->branch )
        {
            $resourceClass = $this->getBranchRespourceClass();
            $dataArrayToChange["branch"] = new $resourceClass($branch);

            $this->handleAccessibleBranchesInfo($dataArrayToChange);

            $this->unsetResouceProp("branch");
        }
    }
    
    protected function getDepartmentResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(DepartmentResource::class);
    }

    protected function appendDepartmentInfo(array &$dataArrayToChange = []): void
    {
        if ($department = $this->department)
        {
            $resourceClass = $this->getDepartmentResourceClass();
            $dataArrayToChange["department"] =  new $resourceClass($department);

            $this->unsetResouceProp("department");
        }
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

    protected function unsetResouceProp(string $propName ) : void
    {
        unset($this->resource->{$propName});
    }

    protected function appendRolePermissions(array &$dataArrayToChange = []): void
    {
        if ($role = $this->role)
        {
            $resourceClass = $this->getRoleResourceClass();
            $dataArrayToChange["role"] = new $resourceClass($role);

            $dataArrayToChange["permissions"] = $role->permissions()->pluck("name")->toArray();

            $this->unsetResouceProp("role");

        }
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
        $initData = [];

        $this->appendRolePermissions($initData);
        $this->appendDepartmentInfo($initData);
        $this->appendBranchInfo($initData);
        $this->appendCompanyLogo($initData);
        
        return array_merge($initData , parent::toArray($request));

    }
}
