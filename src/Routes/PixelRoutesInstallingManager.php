<?php

namespace PixelApp\Routes;

use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppInstallingManager;

class PixelRoutesInstallingManager
{
    protected static ?PixelRoutesInstallingManager $instance = null;

    private function __construct()
    {
        
    }

    public static function Singlton() : self
    {
        if(! static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function installPackageRoutes() : void
    {
        //replacing the route stubs into project routes path
        $this->replacePixelAppRouteStubs();
        
        //setting the required RouteRegistrars (the ones who has an available to define functionality)
        $this->installRouteRegitrars();
    }
     
    protected function getConfigRouteRegistrarsArray(array $routeRegistrars) : array
    {
        $routeRegistrarsConfigData = [];

        array_walk($routeRegistrars , function(&$routeRegistrar) use ($routeRegistrarsConfigData)
        {
            $routeRegistrar->appendRouteRegistrarConfigKey($routeRegistrarsConfigData);
        });

        return $routeRegistrarsConfigData;
    }

    protected function setRouteRegistrarsIntoPixelConfig(array $routeRegistrars) : void
    {
        $configRouteRegistrarsArray = $this->getConfigRouteRegistrarsArray($routeRegistrars);
        PixelConfigManager::setPixelPackageConfigFileKeys($configRouteRegistrarsArray);
    }

    protected function getPackageAllRouteRegistrars() : array
    {
        return PixelRouteManager::getPackageAllRouteRegistrars();
    }

    protected function initRouteRegistrar(string $routeRegistrarClass) : PixelRouteRegistrar
    {
        return new $routeRegistrarClass;
    }

    protected function getAvailableRouteRegistrars() : array
    {
        $routeRegistrars = [];

        foreach($this->getPackageAllRouteRegistrars()  as $routeRegistrarClass)
        {
            $routeRegistrar = $this->initRouteRegistrar($routeRegistrarClass);
            
            if($routeRegistrar->isFuncAvailableToDefine())
            {
                $routeRegistrars[] = $routeRegistrar;
            }
        }

        return $routeRegistrars;
    }

    protected function installRouteRegitrars() : void
    {
        $availableRouteRegistrars = $this->getAvailableRouteRegistrars();
        $this->setRouteRegistrarsIntoPixelConfig($availableRouteRegistrars);
    }
 
    protected function initPixelAppRouteStubsManager() : PixelRouteStubsManager
    {
        return PixelRouteStubsManager::Singleton();
    }
 
    protected function replacePixelAppRouteStubs() : void
    {
        $this->initPixelAppRouteStubsManager()->replacePixelAppRouteStubs();
    }

    protected static function initPixelAppInstallingManager()  :PixelAppInstallingManager
    {
        return PixelAppInstallingManager::Singleton();
    }

    public static function getPixelAppSystemRequirementsCard() : ?PixelAppSystemRequirementsCard
    {
        return static::initPixelAppInstallingManager()->getPixelAppSystemRequirementsCard();
    }

    public static function isInstallingForTenancySupporterApp() : bool
    {
        return static::initPixelAppInstallingManager()->isInstallingForTenancySupporterApp();
    }

    public static function isInstallingForNormalApp() : bool
    {
        return static::initPixelAppInstallingManager()->isInstallingForNormalApp();
    }

    public static function isInstallingForAdminPanel() : bool
    {
        return static::initPixelAppInstallingManager()->isInstallingForAdminPanel();
    }

    public static function isInstallingForTenantApp() : bool
    {
        return static::initPixelAppInstallingManager()->isInstallingForTenantApp();
    }

    public static function DoesItNeedTenantRoutesInstalling() : bool
    {
        return static::initPixelAppInstallingManager()->DoesItNeedTenantRoutesInstalling();
    }

}