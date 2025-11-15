<?php

namespace PixelApp\ServiceProviders;

use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str; 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;
use PixelApp\Routes\PixelRouteBootingManager;

class LaravelServiceProviderStubsManager extends PixelAppStubsManager
{ 
    public function installLaravelProjectServiceProviders() : void
    {
        $this->replaceRouteServiceProvider();
        $this->replaceEventServiceProvider();
        $this->replaceAuthServiceProvider();

    }
    
    protected function getServiceProviderProjectPath(string $relevantPath) : string
    {
        return $this->getServiceProvidersProjectPath() . "/" . $relevantPath;
    }

    protected function getServiceProvidersProjectPath() : string
    {
        return app_path("Providers");
    }

    protected function getLaravelServiceProviderPixelStubsPath() : string
    {
        return realpath(__DIR__ . "/LaravelProviderStubs");
    }
    
    protected function getLaravelServiceProviderPixelStubPath(string $relevantPath) : string
    {
        return $this->getLaravelServiceProviderPixelStubsPath() . "/" . $relevantPath;
    }
    
    protected function getEventServiceProviderStubPath() : string
    {
        return $this->getLaravelServiceProviderPixelStubPath("EventServiceProviderStub.php");
    }

    protected function getAuthServiceProviderStubPath() : string
    {
        return $this->getLaravelServiceProviderPixelStubPath("AuthServiceProviderStub.php");
    }

    protected function getRouteServiceProviderStubPath() : string
    {
        return $this->getLaravelServiceProviderPixelStubPath("RouteServiceProviderStub.php"); 
    }
 
    protected function replaceServiceProviderFile(string $stubPath , string $projectPath) : void
    {
        $stubIdentifier = static::initStubIdentifier($stubPath , $projectPath);
        $this->replaceStubFile($stubIdentifier);
    }
     
    protected static function initStubIdentifier(string $stubPath , string $newPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $newPath);
    }

    protected function replaceEventServiceProvider() : void
    {
        
        $providerNameSpace = "\App\Providers\EventServiceProvider";
        $stubPath = static::getEventServiceProviderStubPath();
        $projectPath = static::getServiceProviderProjectPath("EventServiceProvider.php");
        $this->replaceServiceProviderFile($stubPath , $projectPath);

        
        $this->injectServiceProviderToBootstrapFile($providerNameSpace);
    }

    protected function replaceAuthServiceProvider() : void
    {
        $providerNameSpace = "\App\Providers\AuthServiceProvider";
        $stubPath = static::getAuthServiceProviderStubPath();
        $projectPath = static::getServiceProviderProjectPath("AuthServiceProvider.php");
        $this->replaceServiceProviderFile($stubPath , $projectPath);

        
        $this->injectServiceProviderToBootstrapFile($providerNameSpace);
    }

    protected function replaceRouteServiceProvider() : void
    {
        $stubPath = static::getRouteServiceProviderStubPath();
        $providerNameSpace = "\App\Providers\RouteServiceProvider";
        $projectPath = static::getServiceProviderProjectPath("RouteServiceProvider.php");
        $stubIdentifier = static::initStubIdentifier($stubPath , $projectPath);

        $stubIdentifier->callOnFileContent(function(string $fileContent)
        {
            $tenancyRoutesPlaceHolder = "--loading-tenant-routes--";
         
            $replace =  PixelRouteBootingManager::DoesItNeedTenantRoutesBooting() ?
                        "PixelRouteManager::loadTenantRoutes();" :
                        ""; // an empty string needed to remove placeholder string
    
            return Str::replace($tenancyRoutesPlaceHolder , $replace , $fileContent);
        });

        $this->replaceStubFile($stubIdentifier); 

        $this->injectServiceProviderToBootstrapFile($providerNameSpace);
    }

    protected function injectServiceProviderToBootstrapFile(string $providerClass) :void
    {
        ServiceProvider::addProviderToBootstrapFile($providerClass);
    }
    
}