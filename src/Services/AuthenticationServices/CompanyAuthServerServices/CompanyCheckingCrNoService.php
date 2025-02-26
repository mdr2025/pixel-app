<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyCheckingCrNoService
{  

    protected function getTenantModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }
    protected function fetchTenant(string $crNo) : ?Tenant
    {
        return $this->getTenantModelClass()::where('cr_no', $crNo)->first();
    }

    public function checkCrNoValidity(string $crNo) : JsonResponse
    { 
        if ($this->fetchTenant($crNo)) 
        {
            return response()->json([
                "status" => 200,
                "message" => "Valid",
            ]);
        }
        return response()->json([
            "status" => 424,
            "message" => "Invalid",
        ]); 
    }
}
