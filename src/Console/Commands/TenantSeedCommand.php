<?php

namespace PixelApp\Console\Commands;

use PixelApp\Models\CivilDefensePanel\CompanyModule\TenantCompany;
use Illuminate\Console\Command;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Commands\Seed;
use Stancl\Tenancy\Concerns\HasATenantsOption;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\SeedingDatabase;
use Symfony\Component\Console\Input\InputArgument;

class TenantSeedCommand extends SeedCommand
{
    use HasATenantsOption;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed tenant database(s).';

    protected $name = 'tenant-company:seed';

 
    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {
        foreach (config('tenancy.seeder_parameters') as $parameter => $value) {
            if (! $this->input->hasParameterOption($parameter)) {
                $this->input->setOption(ltrim($parameter, '-'), $value);
            }
        }

        if (! $this->confirmToProceed()) {
            return;
        }

        $companyId = tenant()->getTenantKey();
        $tenant = TenantCompany::find($companyId);
        event(new SeedingDatabase($tenant));
        // parent::handle();
        // event(new DatabaseSeeded($tenant));

    }
}
