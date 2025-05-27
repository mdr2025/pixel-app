<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringCancelingServices;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\TenantConfiguringCancelingEvent;
use PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests\TenantResourcesConfiguringCancelingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\Traits\GeneralValidationMethods;
use Throwable;

/**
 * client : tenant app - central domain
 * server : admin panel
 */
class TenantResourcesConfiguringCancelingServerService
{ 

    use GeneralValidationMethods;

    public function cancel() : JsonResponse
    {
        try{

            $this->initValidator()->validateRequest()->setRequestData();

            $this->fireTenantConfiguringCancelingEvent();

            return $this->getSuccessResponse();

        }catch(Exception $e)
        {
            return $this->getErrorResponse($e);
        }
    }

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( TenantResourcesConfiguringCancelingRequest::class );
    }

    protected function handleFailingException() : ?Throwable
    {
        if($message = $this->data["messeage"] ?? null)
        {
            $code = $this->data["code"] ?? 500;
            return new Exception($message , $code);
        }

        return null;
    }

    protected function getTenantCompanyModelClass() 
    {
        return PixelModelManager::getTenantCompanyModelClass();
    }

    protected function fetchTenantCompany() : TenantCompany
    {
        $modelClass = $this->getTenantCompanyModelClass();
        return $modelClass::where("domain" , $this->data["company_domain"])->firstOrFail() ;
    }

    protected function initTenantConfiguringCancelingEvent() : TenantConfiguringCancelingEvent
    {
        return new TenantConfiguringCancelingEvent( $this->fetchTenantCompany()  , $this->handleFailingException() );
    }

    protected function fireTenantConfiguringCancelingEvent() : void
    {
        event( $this->initTenantConfiguringCancelingEvent() );
    }

    protected function getSuccessResponse() : JsonResponse
    {
        return Response::success([] , "Start To Cancel Tenant Resources Configuring Process !");
    }

    protected function getErrorResponse(Exception $e) : JsonResponse
    {
        return Response::error($e->getMessage());
    }
     
}
