<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Illuminate\Http\JsonResponse; 
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelAppCore\Services\PixelServiceManager;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyCheckingSubDomainService
{ 

    protected function fetchTenant(string $subDomain) : ?Tenant
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return (new $service())->fetchTenantCompany($subDomain);
    }

    public function checkSubDomainAvailability(string $subDomain) : JsonResponse
    {
        if (! $this->fetchTenant($subDomain))
        {
            return response()->json([
                "status" => 200,
                "message" => "available",
            ]);
        }
        return response()->json([
            "status" => 200,
            "message" => "taken",
        ]);
    }
}
