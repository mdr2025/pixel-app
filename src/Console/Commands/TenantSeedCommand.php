<?php

namespace PixelApp\Console\Commands;

use Exception;
use PixelApp\Models\CompanyModule\TenantCompany;
use Illuminate\Console\Command;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Support\Facades\DB;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use Stancl\Tenancy\Commands\Seed;
use Stancl\Tenancy\Concerns\HasATenantsOption;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\SeedingDatabase;
use Symfony\Component\Console\Input\InputArgument;

class TenantSeedCommand extends Seed
{
    use HasATenantsOption;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed an appoved tenant database.';

    protected $name = 'tenant-company:seed {companyDomain : The company domain will be used for fetching  }';

 
    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {

        $this->setTenantsParameterValue();

        parent::handle(); 
    }

    protected function fetchTenantKey() : ?TenantCompany
    {
        $companyDomain = $this->getCompanyDomainParameterValue();
        $tenant = PixelTenancyManager::fetchApprovedTenantForDomain($companyDomain);

        if(!$tenant)
        {
            throw new Exception("Can't seed non approved tenant database !");
        }
        
        return $tenant->getTenantKey();
    }

    protected function getCompanyDomainParameterValue() : string
    {
        return $this->argument('companyDomain') 
        ??
        throw new Exception("companyDomain parameter has not been set ... please try again with writing the comapnyDomain you want to seed its database !") ;
    }

    protected function setTenantsParameterValue() : void
    {
        $tenantKey = $this->fetchTenantKey();
        $this->input->setOption('--tenants' , [$tenantKey]);
    }
}
