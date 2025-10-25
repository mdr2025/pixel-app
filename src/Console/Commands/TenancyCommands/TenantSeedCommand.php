<?php

namespace PixelApp\Console\Commands\TenancyCommands;


use PixelApp\Console\Commands\TenancyCommands\Traits\HasCompanyDomainArgument;
use Stancl\Tenancy\Commands\Seed;

class TenantSeedCommand extends Seed
{ 
    use HasCompanyDomainArgument;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed an appoved tenant database.';

    protected $name = 'tenant-company:seed'; 

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
    
 }
