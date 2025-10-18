<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingProcessJobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Helpers\ResponseHelpers;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringServices\TenantResourcesConfiguringClientService;
use PixelApp\Services\PixelServiceManager;
use Throwable;

class TenantResourcesConfiguringClientServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenant;

    public function __construct(TenantCompany $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {            
        $response = $this->initTenantResourcesConfiguringClientService()->getResponse();
        $this->processResponse($response);   
    }

    protected function initTenantResourcesConfiguringClientService() : TenantResourcesConfiguringClientService
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TenantResourcesConfiguringClientService::class);
        return new $service($this->tenant);
    }

    protected function getFailingExceptionMessage(array | string $messages) : string
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
            $exMessage = $this->getFailingExceptionMessage($responseMessage);
            throw new Exception($exMessage);
        }
    }
    /**
     * @throws Exception
     */
    public function failed(Throwable $exception) : void
    {
        TenantApprovingCancelingJob::dispatch($this->tenant  , $exception->getMessage() , $exception->getCode());

        // TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        // TenantApprovingCancelingJob::dispatch($this->tenant);
        // throw $exception;
    }

}
