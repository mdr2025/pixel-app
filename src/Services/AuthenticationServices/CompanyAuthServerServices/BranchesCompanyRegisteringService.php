<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\BranchesCompanyRegisterRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;

/**
 * @todo : need to check later ... maybe it needs to remove this service
 */
class BranchesCompanyRegisteringService extends CompanyRegisteringService
{
    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(BranchesCompanyRegisterRequest::class);
    }

    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }
    
    protected function anyAdditionActions(): void
    {
        $request = request();
        $mainDomain = $request->main_domain;
        $tenantModelClass = $this->getTenantCompanyModelClass();
        $parent_id = $tenantModelClass::where('domain', $mainDomain)->first()?->id;
        
        $request->merge(['parent_id' => $parent_id, 'rc_no' => null]);
    }
}
