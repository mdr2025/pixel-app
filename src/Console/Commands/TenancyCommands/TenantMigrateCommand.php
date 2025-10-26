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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant-company:migrate {companyDomain : The company domain will be used for fetching}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--path=* : The path(s) to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--schema-path= : The path to a schema dump file}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--seeder= : The class name of the root seeder}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate migration files of an appoved tenant database.';

    protected $name = 'tenant-company:migrate';

    /**
     * Override to provide custom tenant command name.
     * This is used by ExtendsLaravelCommand trait from parent class.
     *
     * @return string
     */
    protected static function getTenantCommandName(): string
    {
        return 'tenant-company:migrate';
    }
    
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
        // Call grandparent constructor directly (Illuminate\Console\Command)
        \Illuminate\Console\Command::__construct();

        //when singature is set, we need to specify the parameters  
        $this->specifyParameters();
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
       
        $this->setTenantsParameterValue();

        // Now call parent handle() which will use $this->migrator and $this->dispatcher
        return parent::handle();
    }
}
