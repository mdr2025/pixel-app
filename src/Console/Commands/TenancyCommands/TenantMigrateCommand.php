<?php

namespace PixelApp\Console\Commands\TenancyCommands;

use PixelApp\Console\Commands\TenancyCommands\Traits\HasCompanyDomainArgument;
use Stancl\Tenancy\Commands\Migrate;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;

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
    
    /**
     * Create a new command instance.
     * 
     * We override the constructor to avoid dependency resolution at registration time.
     * Dependencies will be resolved lazily in handle() method instead.
     *
     * @return void
     */
    public function __construct()
    {
        // Don't call parent::__construct() to avoid requiring dependencies at registration time
        // Call grandparent constructor directly
        \Illuminate\Console\Command::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Resolve dependencies lazily from the application container
        // At this point, MigrationServiceProvider will be loaded automatically when requesting 'migrator'
        $this->migrator = $this->laravel->make('migrator');
        $this->dispatcher = $this->laravel->make(Dispatcher::class);
        
        // Now call parent handle() which will use $this->migrator and $this->dispatcher
        return parent::handle();
    }
}
