<?php

namespace PixelApp\Console\Commands\PixelAppInitCommands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppInstallingManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppUninstallingManager;
use PixelApp\Helpers\PixelGlobalHelpers;
use PixelApp\Http\Middleware\PixelMiddlewareManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Routes\PixelRouteManager;
use PixelApp\ServiceProviders\LaravelServiceProviderManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;
use Symfony\Component\Process\Process;
use Throwable;

class PreparePixelApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pixel-app:prepare-to-use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preparing Pixel App based on pixel-app-package-config values to be ready to use without any extra configuration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
  /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $this->installApp();

        }catch(Throwable $ex)
        {
            $this->uninstallApp();
        }
    }

    protected function askForAppType() : string
    {
        $validTypes = PixelAppInstallingManager::getValidAppTypes();
        $defaultAppType = PixelAppInstallingManager::getDefaultAppType();
        $selectedAppType = $this->choice(
                                'Select the App type , the default type is ' .  $defaultAppType,
                                $validTypes
                            );
        return $selectedAppType ?? $defaultAppType;
    }

    protected function installApp() : void
    {
        $appType = $this->askForAppType();
        PixelAppInstallingManager::install($appType);
        $this->info("pixel app package has been installed successfully !");
    }
    
    protected function uninstallApp() : void
    {
        PixelAppUninstallingManager::uninstall();
    }
    // protected function appendServiceProviderToPixelConfigDefaultProviders() : void
    // {
    //     $pixelConfig = ConfigValueManager::getPixelAppConfigArray();
    //     $pixelDefaultProvidersKey = ConfigValueManager::getPixelAppDefaultProvidersKeyName();
    //     $tenancyProviderClass = TenancyServiceProvider::class;

    //     if(isset($pixelConfig[$pixelDefaultProvidersKey]))
    //     {
    //         $pixelConfig[$pixelDefaultProvidersKey][] = $tenancyProviderClass;

    //     }else{
    //         $pixelConfig[$pixelDefaultProvidersKey] = [ $tenancyProviderClass ];
    //     }
        
    //     File::put(config_path( ConfigValueManager::getPixelConfigProjectRelevantPath() ) , $pixelConfig);
    // }


    // protected function requireStanclTenancyPackage() : void
    // {
    //     $process = new Process(['composer', 'require', 'stancl/tenancy:^3.6']);
    //     $process->setTimeout(null);
    //     $process->run();

    //     if (!$process->isSuccessful()) 
    //     {
    //         $this->info('------------------------------------------------\n');
    //         $this->error('Failed to install the package. Error: ' . $process->getErrorOutput() . "\n");
    //         $this->info('------------------------------------------------\n');
    //         return;
    //     }

    //     $this->info('------------------------------------------------\n');
    //     $this->info('stancl/tenancy:^3.6 Package installed successfully\n.');
    //     $this->info('------------------------------------------------\n');
    // }

}
