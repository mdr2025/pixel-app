<?php

namespace PixelApp\Console\Commands;

use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApproved;
use PixelApp\Models\CivilDefensePanel\CompanyModule\TenantCompany;
use Illuminate\Console\Command;

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
        $company = $this->getCompany(); 

        $approved = $company?->approveCompany()->save() ?? false;
        if($approved)
        {
            event( new TenantCompanyApproved($company) );
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
}
