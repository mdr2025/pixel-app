<?php

namespace PixelApp\ServiceProviders;

use Illuminate\Console\Scheduling\Schedule;
use CRUDServices\ConfigManagers\ConfigManager;
use ExpImpManagement\ExportersManagement\Exporter\Exporter;
use Illuminate\Support\ServiceProvider;
use PixelApp\Console\Commands\CreateCrudService;
use PixelApp\Console\Commands\CreateStatisticCommand;
use PixelApp\Console\Commands\RefreshTheProject;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelExceptionHandlerInterface;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Console\Commands\CreateInterfaceCommand;
use PixelApp\Console\Commands\CreateTrait;
use PixelApp\Console\Commands\PassportConfiguringCommands\PixelAppClientCustomCommand;
use PixelApp\Console\Commands\PassportConfiguringCommands\PixelPassportConfiguringCommand;
use PixelApp\Console\Commands\PassportConfiguringCommands\PixelServerAppClientCredentialSetupCommand;
use PixelApp\Console\Commands\PixelAppInitCommands\DefaultFontsHandling;
use PixelApp\Console\Commands\PixelAppInitCommands\PreparePixelApp;
use PixelApp\Console\Commands\TenancyCommands\TenantCompanyApprovingTest;
use PixelApp\Console\Commands\TenancyCommands\TenantMigrateCommand;
use PixelApp\Console\Commands\TenancyCommands\TenantSeedCommand;
use PixelApp\CustomLibs\PixelCycleManagers\IOEncryptionManager\IOEncryptionManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Exceptions\Handler;
use PixelApp\Exceptions\PixelAppExceptionHandler;
use PixelApp\Jobs\TenantCompanyJobs\ExpImpManagementHandlingJobs\OldDataExportersDeleterAltJob;
use PixelApp\Jobs\UsersModule\AuthJobs\UserDeletableTokensHandlerJob;
use PixelApp\PixelMacroableExtenders\PixelMacroableExtender;
use PixelApp\ServiceProviders\Traits\ConfigFilesHandling;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\BranchRepositoryInterfaces\BranchRepositoryInterface;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\DepartmentRepositoryInterface;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\BranchRepository;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\DepartmentRepository;
use Throwable;

class PixelAppPackageServiceProvider extends ServiceProvider
{ 
    
    use ConfigFilesHandling;
 
    public function register()
    {
        //merging config files
        $this->mergeConfigFiles(); 
        $this->registerIOEncryptionObjects();

        $this->registerRepositryInterfaces();

        // $this->registerHttpKernel();
        $this->registerConsoleKernel();
        $this->registerCustomExceptionHandler();

        $this->disableEventDiscovery();

        $this->setOldDataExportersDeleterAltJob();
    }

    public function boot()
    {
        $this->prepareConfigFilesPublishing();
  
        PixelTenancyManager::RegisterPixelTenancyOnNeed($this->app);

        $this->bootIOEncryptionFuncs();
        $this->scheduledObjectsHandling();
        $this->defineCommands();
        $this->handlePixelAppViews();
         
    }


    // protected function registerHttpKernel() : void
    // {
    //     $this->app->singleton(HttpKernel::class , '\App\Http\Kernel.php');
    // }

    protected function registerConsoleKernel() : void
    {
        // $this->app->singleton(ConsoleKernel::class , '\App\Console\Kernel.php');
    }

    protected function registerCustomExceptionHandler() : void
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);
    }

    /**
     * to avoid auto discovering .... the events must be bind manualy in EventServiceProvider to avoid Listeners and Events Auto Discovering and Scaning
     */
    protected function disableEventDiscovery() : void
    {
        EventServiceProvider::disableEventDiscovery();
    }

    protected function defineCommands() : void
    {
        if ($this->app->runningInConsole())
        {
            // Register TenantMigrateCommand with factory that returns instance without dependencies
            $this->app->bind(TenantMigrateCommand::class, function ($app) {
                return new TenantMigrateCommand();
            });
            
            $this->commands([
                PreparePixelApp::class,
                PixelPassportConfiguringCommand::class,
                PixelAppClientCustomCommand::class,
                PixelServerAppClientCredentialSetupCommand::class,
                DefaultFontsHandling::class,
                CreateInterfaceCommand::class,
                CreateTrait::class,
                RefreshTheProject::class,
                TenantCompanyApprovingTest::class,
                TenantSeedCommand::class,
                TenantMigrateCommand::class,  // Will handle dependencies in its own handle() method
            ]);
        }
    }
 
    protected function getProjectViewsPath() : string
    {
        return resource_path('views/vendor/pixel-app');
    }

    protected function getPixelAppViewsPath() : string
    {
        return realpath( __DIR__ . "/resources/views"  );
    }

    protected function makeViewsPublishable() : void
    {
        $this->publishes([
            $this->getPixelAppViewsPath() => $this->getProjectViewsPath()
        ] , "pixel-app-views");
    }

    protected function loadPixelAppViews() : void
    {
        $this->loadViewsFrom( $this->getPixelAppViewsPath() , "pixel-app");
    }
    
    protected function handlePixelAppViews() : void
    {
        $this->loadPixelAppViews();
        $this->makeViewsPublishable();
    }
    protected function registerIOEncryptionObjects() : void
    {
        IOEncryptionManager::registerObjects($this->app);
    }

    protected function bootIOEncryptionFuncs() : void
    {
        IOEncryptionManager::bootFuncs();
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduledObjectsHandling()
    {
        $this->app->booted(function ()
        {
            $schedule = $this->app->make(Schedule::class);
            $schedule->job(UserDeletableTokensHandlerJob::class)->daily()->at('00:00');
        });
        
    }

    protected function setOldDataExportersDeleterAltJob() : void
    {
        Exporter::setOldDataExportersDeleterAlternativeJob(
                                                              OldDataExportersDeleterAltJob::class 
                                                          );
    }

    protected function registerRepositryInterfaces()  :void
    {
        $this->registerDepartmentRepositryInterface() ;
        $this->registerBranchRepositoryInterface();
    }

    protected function registerDepartmentRepositryInterface() : void
    {
        $this->app->singleton(DepartmentRepositoryInterface::class , DepartmentRepository::class);
    }

    
    protected function registerBranchRepositoryInterface() : void
    {
        $this->app->singleton(BranchRepositoryInterface::class , BranchRepository::class);
    }
}
