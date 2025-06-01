<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class ApprovedTenantCompanyIDSFetchingService
{
    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }

    protected function fetchTenantComopanyIDs() : array
    {
        return $this->getTenantCompanyModelClass()::isNotPending()->pluck("id")->toArray();
    }

    public function getTenantCompanyIDS() : JsonResponse
    {
        try
        {
            $ids = $this->fetchTenantComopanyIDs();
            return Response::success($ids);

        }catch(Exception $exception)
        {
            return Response::error($exception->getMessage());
        }
        
    }

}
