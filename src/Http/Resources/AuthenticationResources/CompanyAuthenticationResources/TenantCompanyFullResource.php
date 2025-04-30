<?php

namespace PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\CompanyModule\TenantCompany;

/**
 * @property TenantCompany $resource
 */
class TenantCompanyFullResource extends JsonResource
{
    protected function appendContactsData(array $attrs , $request) : array
    {
        /**
         * @TODO todo later
         */
        return $attrs;
    }

    protected function getTenantCompanyProfileResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(TenantCompanyProfileResource::class);
    }

    protected function getTenantProfileAttrs($request) : array
    {
        $resourceClass = $this->getTenantCompanyProfileResourceClass();
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
        $tenantAttrs = $this->getTenantProfileAttrs($request);
        return $this->appendContactsData($tenantAttrs , $request);
    }
}
