<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ClientSideFailingProcessJobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Helpers\ResponseHelpers;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\PixelServiceManager;
use Throwable;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringCancelingServices\TenantResourcesConfiguringCancelingClientService;
/**
 * @property TenantCompany $tenant
 */
class TenantConfiguringCancelingJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected TenantCompany $tenant;
    protected  ?string $configuringFailingExceptionMessage = null ;
    protected ?int $configuringFailingExceptionCode = null ;

    public function __construct(
                                TenantCompany $tenant ,
                                ?string $configuringFailingExceptionMessage = null ,
                                ?int $configuringFailingExceptionCode = null 
                               )
    {
        $this->tenant = $tenant;
        $this->configuringFailingExceptionMessage = $configuringFailingExceptionMessage;
        $this->configuringFailingExceptionCode = $configuringFailingExceptionCode;
    }
    
    /**
     * @return void
     */
    public function handle()
    {
        $response = $this->initTenantResourcesConfiguringCancelingClientService()->getResponse();
        $this->processResponse($response);
    }

    protected function initTenantResourcesConfiguringCancelingClientService() : TenantResourcesConfiguringCancelingClientService
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantResourcesConfiguringCancelingClientService::class);
        $failingException = $this->getConfiguringFailingException();
        return new $service($this->tenant , $failingException );
    }

    protected function getConfiguringFailingException() : ?Exception
    {
        if($this->configuringFailingExceptionMessage )
        {
            return new Exception($this->configuringFailingExceptionMessage , $this->configuringFailingExceptionCode ?? 500);
        }
        
        return null;
    }

    protected function getClientServiceFailingExceptionMessage(array | string $messages) : string
    {
        if(is_array($messages) && !empty($messages))
        {
            return join( " , " , $messages );
        }

        if(is_string($messages) && $messages != "")
        {
            return $messages;
        }

        return "Tenant Configuring Process has Failed !";
    }

    protected function processResponse(JsonResponse $response) : void
    {
        if(ResponseHelpers::getResponseStatus($response) == false)
        {
            $responseMessage = ResponseHelpers::getResponseMessages($response);
            $exMessage = $this->getClientServiceFailingExceptionMessage($responseMessage);
            throw new Exception($exMessage);
        }
    }
    
}
