<?php

namespace PixelApp\Console\Commands\TenancyCommands;

use PixelApp\Console\Commands\TenancyCommands\Traits\HasCompanyDomainArgument;
use Stancl\Tenancy\Commands\Migrate; 

class TenantMigrateCommand extends Migrate
{ 
    use HasCompanyDomainArgument;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate migration files of an appoved tenant database.';

    protected $name = 'tenant-company:migrate'; 
}
