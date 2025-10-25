<?php

namespace PixelApp\Console\Commands\TenancyCommands\Traits;

use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use Symfony\Component\Console\Input\InputArgument;
use Exception;

trait HasCompanyDomainArgument
{
    
    protected function getArguments()
    {
        return array_merge([
            new InputArgument("companyDomain" , null , 'The company domain will be used for fetching')
        ], parent::getArguments());
    }
    
 
    protected function fetchTenant() : ?TenantCompany
    {
        $companyDomain = $this->getCompanyDomainParameterValue();
        $tenant = PixelTenancyManager::fetchApprovedTenantForDomain($companyDomain);

        if(!$tenant)
        {
            throw new Exception("Can't seed non approved tenant database !");
        }
        
        return $tenant;
    }

    protected function getCompanyDomainParameterValue() : string
    {
        return $this->argument('companyDomain') 
        ??
        throw new Exception("companyDomain parameter has not been set ... please try again with writing the comapnyDomain you want to seed its database !") ;
    } 

    protected function setTenantsParameterValue() : void
    {
        $tenant = $this->fetchTenant();

        $this->input->setOption('tenants' , [ $tenant ]);
    }

}