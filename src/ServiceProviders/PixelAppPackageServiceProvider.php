<?php

namespace PixelApp\ServiceProviders;

use Illuminate\Console\Scheduling\Schedule;
use CRUDServices\ConfigManagers\ConfigManager;
use Illuminate\Support\ServiceProvider;
use PixelApp\Console\Commands\CreateCrudService;
use PixelApp\Console\Commands\CreateStatisticCommand;
use PixelApp\Console\Commands\RefreshTheProject;
use PixelApp\Console\Commands\TenantCompanyApprovingTest;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelExceptionHandlerInterface;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Console\Commands\CreateInterfaceCommand;
use PixelApp\Console\Commands\CreateTrait;
use PixelApp\Console\Commands\PassportConfiguringCommands\PixelAppClientCustomCommand;
use PixelApp\Console\Commands\PassportConfiguringCommands\PixelPassportConfiguringCommand;
use PixelApp\Console\Commands\PixelAppInitCommands\DefaultFontsHandling;
use PixelApp\Console\Commands\PixelAppInitCommands\PreparePixelApp;
use PixelApp\Console\Commands\TenantSeedCommand;
use PixelApp\CustomLibs\PixelCycleManagers\IOEncryptionManager\IOEncryptionManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Exceptions\PixelAppExceptionHandler;
use PixelApp\Jobs\UsersModule\AuthJobs\UserRevokedAccessTokensDeleterJob;
use PixelApp\PixelMacroableExtenders\PixelMacroableExtender;
use PixelApp\ServiceProviders\Traits\ConfigFilesHandling;
use Throwable;

class PixelAppPackageServiceProvider extends ServiceProvider
{ 
    
    use ConfigFilesHandling;
 
    public function register()
    {
        //merging config files
        $this->mergeConfigFiles(); 
        $this->registerIOEncryptionObjects();
        // Bind the custom exception handler
        //$this->app->singleton(LaravelExceptionHandlerInterface::class, PixelAppExceptionHandler::class);
    }

    public function boot()
    {
        $this->prepareConfigFilesPublishing();
  
        PixelTenancyManager::RegisterPixelTenancyOnNeed($this->app);

        $this->booIOEncryptionFuncs();
        $this->scheduledObjectsHandling();
        $this->defineCommands();
        $this->handlePixelAppViews();
         
    }

    protected function defineCommands() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PreparePixelApp::class,
                PixelPassportConfiguringCommand::class,
                PixelAppClientCustomCommand::class,
                DefaultFontsHandling::class,
                CreateInterfaceCommand::class,
                CreateTrait::class,
                RefreshTheProject::class,
                TenantCompanyApprovingTest::class,
                TenantSeedCommand::class
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

    protected function booIOEncryptionFuncs() : void
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
        // $this->app->booted(function ()
        // {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->job(UserRevokedAccessTokensDeleterJob::class)->daily()->at('00:00');
        // });
        
    }
}
