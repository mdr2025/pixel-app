<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\PixelServiceManager;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyCheckingSubDomainService
{ 

    protected function fetchTenant(string $subDomain) : ?Tenant
    {
        return PixelTenancyManager::fetchTenantForServerSide($subDomain);
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
