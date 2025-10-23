<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantConfiguringProcessJobs;
 
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ClientSideFailingProcessJobs\TenantConfiguringCancelingJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\UserEncapsulatedFunc\RegistrableUserHandlers\RegistrableDefaultSuperAdminApprover;
use Throwable;


class TenantSuperAdminSeederJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TenantCompany $tenant;
    protected ?Model $admin = null;


    public function __construct(TenantCompany $tenant)
    {
        $this->tenant = $tenant;
    }

    
    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->saveDefaultAdminIntoTenantDatabase();
    }


    /**
     * @param Throwable $exception
     * @return void
     * @throws Exception
     * When failed ... this method will be called by JobPipeLine object
     * Tenant database will be deleted if the user can't be seeded
     */
    public function failed(\Throwable $exception) : void
    {

        if(PixelTenancyManager::isItMonolithTenancyApp())
        {

            TenantApprovingCancelingJob::dispatch($this->tenant  , $exception->getMessage() , $exception->getCode());

        }elseif(PixelTenancyManager::isItTenantApp())
        {
            TenantConfiguringCancelingJob::dispatch($this->tenant->domain  , $exception->getMessage() , $exception->getCode());
        }
       
    }

    /**
     * @throws Exception
     */
    protected function getTenantDefaultAdmin() : CompanyDefaultAdmin
    {
        if (! $defaultAdmin = $this->tenant->defaultAdmin )
        {
            throw new Exception("Failed to seed tenant company super admin ... No related default admin is found ! ");
        }
        return $defaultAdmin;
    }

    /**
     * @throws Exception
     */
    protected function initRegistrableAdminApprover() : RegistrableDefaultSuperAdminApprover
    {
        return new RegistrableDefaultSuperAdminApprover( $this->getTenantDefaultAdmin() );
    }

    /**
     * @return Model
     * @throws Exception
     */
    protected function getApprovedRegistrableAdmin() : Model
    {
        return $this->initRegistrableAdminApprover()->approveAdmin() ;
    }

    /**
     * @throws Exception
     */
    protected function saveDefaultAdminIntoTenantDatabase() : self
    {
        $this->tenant->run(function ()
        {
            $this->getApprovedRegistrableAdmin()->save();
        });
        return $this;
    }
}
