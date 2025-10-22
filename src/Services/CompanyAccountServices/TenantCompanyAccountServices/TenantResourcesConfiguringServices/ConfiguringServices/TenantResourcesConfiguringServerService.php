<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringServices;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\RequestTenantAppToConfigureApprovedTenant;
use PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests\TenantResourcesConfiguringRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Services\Traits\GeneralValidationMethods;

/**
 * client : admin panel 
 * server : tenant app central domain
 */
class TenantResourcesConfiguringServerService
{ 

    use GeneralValidationMethods;

    public function configure() : JsonResponse
    {
        try{

            $this->initValidator()->validateRequest()->setRequestData();

            $this->fireTenantConfiguringRequestEvent();

            return $this->getSuccessResponse();

        }catch(Exception $e)
        {
            return $this->getErrorResponse($e);
        }
    }

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( TenantResourcesConfiguringRequest::class );
    }

    protected function initTenantConfiguringRequestEvent() : RequestTenantAppToConfigureApprovedTenant
    {
        return new RequestTenantAppToConfigureApprovedTenant( $this->data["company_domain"] );
    }

    protected function fireTenantConfiguringRequestEvent() : void
    {
        event( $this->initTenantConfiguringRequestEvent() );
    }

    protected function getSuccessResponse() : JsonResponse
    {
        return Response::success([] , "Tenant Resources Configuring Process Has Been Appended To Process Queue Successfully !");
    }

    protected function getErrorResponse(Exception $e) : JsonResponse
    {
        return Response::error($e->getMessage());
    }
     
}
