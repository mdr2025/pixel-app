<?php
namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Console\PixelConsoleManager;
use PixelApp\Helpers\PixelGlobalHelpers;
use PixelApp\Http\Middleware\PixelMiddlewareManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Database\PixelDatabaseManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Routes\PixelRouteManager;
use PixelApp\ServiceProviders\LaravelServiceProviderStubsManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

class PixelAppInstallingManager
{
    protected static ?PixelAppInstallingManager $instance = null;
    protected ?PixelAppSystemRequirementsCard $requirementCard = null;
    protected ?string $appType = null;

    private function __construct()
    {
    }

    public static function Singleton() : self
    {
        if(!static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function install(PixelAppSystemRequirementsCard $requirementCard)
    {

        /**
         * 
         * configuring pixel app package config file before replacing it into config project path
         *
         * replacing config files and merging their data to be readable by config() helper function
         */
        $this->HandleFirstTimeConfig($requirementCard);
        
        //replacing the other application stubs
        $this->installLaravelServiceProviders();
        $this->installPackageMiddlewareStubs();
        $this->installPackageModels();
        $this->installAppDatabaseFiles();
        $this->installConsoleObjects();

        //handling pixel-dompdf package needed font files
        $this->handleDefaultFonts();
    }
    
    protected function HandleFirstTimeConfig(PixelAppSystemRequirementsCard $requirementCard) : void
    {

        $this->startInstallingProcess($requirementCard);

        //configuring pixel app package config file before replacing it into config project path
        $this->definePixelAppFunctinallities();

        //replacing config files and merging their data to be readable by config() helper function
        $this->installPackageConfigFiles();

    }

    protected function startInstallingProcess(PixelAppSystemRequirementsCard $requirementCard)
    {
        $this->usePixelAppSystemRequirementsCard($requirementCard);

        $this->configureAppType();
    }

    protected function checkInstallingProcessStart() : void
    {
        if(!$this->requirementCard)
        {
            throw new Exception("No Pixel app installing process is start !");
        }
    }

    protected function usePixelAppSystemRequirementsCard(PixelAppSystemRequirementsCard $requirementCard) : void
    {
        $this->requirementCard = $requirementCard;
    }

    public function getPixelAppSystemRequirementsCard() : ?PixelAppSystemRequirementsCard
    {
        $this->checkInstallingProcessStart();

        return $this->requirementCard;
    }
 
    protected function changePixelAppConfigValue(string $key , mixed $value) : void
    {
        $changes = [$key => $value];
        PixelConfigManager::setPixelPackageConfigFileKeys($changes);
    }

    protected function setAppType(string $appType) : void
    {
        $this->appType = $appType;
    }

    public function getAppType() : ?string
    {
        $this->checkInstallingProcessStart();

        return $this->appType;
    }

    /**
     * Determines if the insatlation process is run for an admin panel app
     */
    public function isInstallingForAdminPanel() : bool
    {
        $this->checkInstallingProcessStart();

        return $this->getAppType() == PixelAppTypeEnum::ADMIN_PANEL_APP_TYPE;
    }

    
    /**
     * Determines if the insatlation process is run for an tenant app
     */
    public function isInstallingForTenantApp() : bool
    {
        $this->checkInstallingProcessStart();

        return $this->getAppType() == PixelAppTypeEnum::TENANT_APP_TYPE;
    }

    /**
     * Determines if the insatlation process is run for a normal app
     *
     */
    public function isInstallingForNormalApp() : bool
    {
        $this->checkInstallingProcessStart();

        return $this->getAppType() == PixelAppTypeEnum::NORMAL_APP_TYPE;
    }

    
    /**
     * Determines if the insatlation process is run for a monolith app
     */
    public function isInstallingForMonolithApp() : bool
    {
        $this->checkInstallingProcessStart();

        return $this->getAppType() == PixelAppTypeEnum::MONOLITH_TENANCY_APP_TYPE;
    }

    
    /**
     * Determines if the insatlation process is run for a tenancy supporter app
     */
    public function isInstallingForTenancySupporterApp() : bool
    {
        $this->checkInstallingProcessStart();

        return $this->getAppType() !== PixelAppTypeEnum::NORMAL_APP_TYPE;
    }

    public static function getDefaultAppType() : string
    {
        return PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE;
    }

    public static function getValidAppTypes() : array
    {
        return [
            PixelAppTypeEnum::TENANT_APP_TYPE,
            PixelAppTypeEnum::ADMIN_PANEL_APP_TYPE ,
            PixelAppTypeEnum::MONOLITH_TENANCY_APP_TYPE ,
            PixelAppTypeEnum::NORMAL_APP_TYPE
        ];
    }

    protected function configureAppType() : void
    {
        $appType = $this->getPixelAppSystemRequirementsCard()->getSystemType();

        if(! in_array($appType , $this::getValidAppTypes()))
        {
            dd("The selected app type is not valid !");
        }

        $this->setAppType($appType); 

        $this->changePixelAppConfigValue(
                                            PixelConfigManager::getPixelAppTypeConfigKeyName() ,
                                            $appType
                                         );
    }
    
    
    protected function installPackageConfigFiles() : void
    {
        PixelConfigManager::installPackageConfigFiles();
    }

    protected function definePixelAppFunctinallities() : void
    { 
        PixelRouteManager::installPackageRoutes();
    }
 
    protected function initLaravelServiceProviderStubsManager() : LaravelServiceProviderStubsManager
    {
        return LaravelServiceProviderStubsManager::Singleton();
    }

    protected function installLaravelServiceProviders() : void
    {
        $this->initLaravelServiceProviderStubsManager()->installLaravelProjectServiceProviders(); 
    }
 
    protected function installPackageMiddlewareStubs() : void
    {
        PixelMiddlewareManager::installPackageMiddlewareStubs();
    }

    protected function installPackageModels() : void
    {
        PixelModelManager::installPackageModels();
    }

    protected function installAppDatabaseFiles() : void
    {
        PixelDatabaseManager::installAppDatabaseFiles();
    }

    protected function installConsoleObjects() : void
    {
        PixelConsoleManager::installConsoleObjects();
    }

    protected function handleDefaultFonts() : void
    {
        Artisan::call("pixel-app:handle-default-fonts");
    }
 
 
    public function DoesItNeedTenantRoutesInstalling() : bool
    {
        return static::isInstallingForMonolithApp() || static::isInstallingForTenantApp();
    }

}