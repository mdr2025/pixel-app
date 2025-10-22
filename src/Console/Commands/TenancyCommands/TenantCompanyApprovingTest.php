<?php

namespace PixelApp\Console\Commands\TenancyCommands;

use PixelApp\Models\CompanyModule\TenantCompany;
use Illuminate\Console\Command;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\ApprovedByAdminPanel;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\ApprovedByCentralApp;

class TenantCompanyApprovingTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant-company:approve {companyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getCompany() : ?TenantCompany
    {
        $companyId = $this->argument("companyId");
        return TenantCompany::find($companyId);
    }
    
    protected function approveCompany() : bool
    {

        if(!$this->canApproveTenantCompany())
        {
            return false;
        }

        
        $company = $this->getCompany(); 

        $approved = $company?->approveCompany()->save() ?? false;

        if($approved)
        {
            $this->fireApprovingEvent($company);
        }

        return $approved;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
//$row = CompanyDefaultAdmin::join('erp_system6.users as db2','tenant_default_admins.email','=','db2.email')
//    ->select([DB::raw('tenant_default_admins.email as defaultAdminEmail') , DB::raw('db2.email as AdminEmail')])
//    ->first();
//        dd($row->toArray());
        return (int) $this->approveCompany();
    }

    protected function canApproveTenantCompany() : bool
    {
        return $this->isBootingForAdminPanelApp()
               ||
               PixelAppBootingManager::isBootingForMonolithTenancyApp();
    }

    protected function isBootingForAdminPanelApp()
    {
        return PixelAppBootingManager::isBootingForAdminPanelApp();
    }

    protected function isBootingForMonolithTenancyApp()
    {
        return PixelAppBootingManager::isBootingForMonolithTenancyApp();
    }

    protected function fireApprovingEvent(TenantCompany $company) : void
    {
        if($this->isBootingForAdminPanelApp())
        {
            event(new ApprovedByAdminPanel($company));
        }

        if($this->isBootingForMonolithTenancyApp())
        {
            event(new ApprovedByCentralApp($company));
        }
    }
}
