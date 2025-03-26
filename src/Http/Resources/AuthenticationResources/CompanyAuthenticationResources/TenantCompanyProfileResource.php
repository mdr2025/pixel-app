<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources;
 
use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources\DefaultAdminResource;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources\TenantCompanyResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;

/**
 * @property TenantCompany $resource
 */
class TenantCompanyProfileResource extends JsonResource
{
    protected function appendPackageData(array $attrs , $request) : array
    {
        /**
         * @TODO todo later
         */
        return $attrs;
    }

    protected function getTenantCompanyResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(TenantCompanyResource::class);
    }

    protected function getDefaultAdminResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(DefaultAdminResource::class);
    }

    protected function appendDefaultAdminData(array $attrs , $request) : array
    {
        $resourceClass = $this->getDefaultAdminResourceClass();
        $attrs["defaultAdmin"] = (new $resourceClass( $this->resource->defaultAdmin ))->toArray($request);
        return $attrs;
    }
    protected function getTenantMainAttrs($request) : array
    {
        $resourceClass = $this->getTenantCompanyResourceClass();
        return (new $resourceClass($this->resource))->toArray($request);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tenantAttrs = $this->getTenantMainAttrs($request);
        $tenantAttrs = $this->appendDefaultAdminData($tenantAttrs , $request);
        return $this->appendPackageData($tenantAttrs , $request);
    }
}
