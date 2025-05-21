<?php

namespace PixelApp\Routes;

use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\PixelConfigManager;

class PixelRoutesInstaller
{
    protected ?PixelRoutesInstaller $instance;

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

    public function installPackageRoutes(PixelAppSystemRequirementsCard $requirementCard) : void
    {
        //replacing the route stubs into project routes path
        $this->replacePixelAppRouteStubs();
        
        //setting the required RouteRegistrars (the ones who has an available to define functionality)
        $this->installRouteRegitrars($requirementCard);
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

    protected function getAvailableRouteRegistrars(PixelAppSystemRequirementsCard $requirementCard) : array
    {
        $routeRegistrars = [];

        foreach($this->getPackageAllRouteRegistrars()  as $routeRegistrarClass)
        {
            $routeRegistrar = $this->initRouteRegistrar($routeRegistrarClass);
            
            if($routeRegistrar->isFuncAvailableToDefine($requirementCard))
            {
                $routeRegistrars[] = $routeRegistrar;
            }
        }

        return $routeRegistrars;
    }

    protected function installRouteRegitrars(PixelAppSystemRequirementsCard $requirementCard) : void
    {
        $availableRouteRegistrars = $this->getAvailableRouteRegistrars($requirementCard);
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

}