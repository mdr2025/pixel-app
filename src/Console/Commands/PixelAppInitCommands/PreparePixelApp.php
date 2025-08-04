<?php

namespace PixelApp\Console\Commands\PixelAppInitCommands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
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
    protected PixelAppSystemRequirementsCard $requirementCard;
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
 
    protected function initPixelAppSystemRequirementsCard() : void
    {
        $this->requirementCard = PixelAppSystemRequirementsCard::Singleton();
    }
   
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->initPixelAppSystemRequirementsCard();
        $this->askUserToDescribeSystemProps();

        try{
            $this->installApp();

        }catch(Throwable $ex)
        {
            $this->uninstallApp();
        }
    }

    protected function askUserToDescribeSystemProps() : void
    {
        $this->configureSystemType();
        $this->configureDepartments();
        $this->configureBranches();
        $this->configureCities();
        $this->configureAreas();
        $this->configureCurrencies();
        $this->configureUserSignature();
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

    protected function configureSystemType(): void
    {
        $systemType = $this->askForAppType();
        $this->requirementCard->setSystemType($systemType);
    }

    protected function configureDepartments(): void
    {
        if ($this->confirm('Is Departments functionality required ?', false)) {
            $this->requirementCard->requireDepartmentsFunc();
        }
    }

    protected function configureBranches(): void
    {
        if ($this->confirm('Is Branches functionality Required?', false)) {
            $this->requirementCard->requireBranchesFunc();
        }
    }

    protected function configureCities(): void
    {
        if ($this->confirm('Is Cities functionality Required?', false)) {
            $this->requirementCard->requireCitiesFunc();
        }
    }

    protected function configureAreas(): void
    {
        if ($this->confirm('Is Areas functionality Required ?', false)) {
            $this->requirementCard->requireAreasFunc();
        }
    }

    protected function configureCurrencies(): void
    {
        if ($this->confirm('Is Currencies functionality Required?', false)) {
            $this->requirementCard->requireCurrenciesFunc();
        }
    }

    protected function configureUserSignature(): void
    {
        if ($this->confirm('Is User Signature functionality Required?', false)) {
            $this->requirementCard->requireUserSignatureFunc();
        }
    }

    protected function initPixelAppInstallingManager() : PixelAppInstallingManager
    {
        return PixelAppInstallingManager::Singleton();
    }

    protected function installApp() : void
    {
        $this->initPixelAppInstallingManager()->install($this->requirementCard);

        $this->info("pixel app package has been installed successfully !");
    }
    
    protected function uninstallApp() : void
    {
        PixelAppUninstallingManager::uninstall($this->requirementCard);
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
