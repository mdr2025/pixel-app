<?php

namespace PixelApp\ServiceProviders;
 
use Illuminate\Support\Str; 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

class LaravelServiceProviderStubsManager extends PixelAppStubsManager
{ 
    public function installLaravelProjectServiceProviders() : void
    {
        $this->replaceRouteServiceProvider();
        $this->replaceEventServiceProvider();
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
        $stubPath = static::getEventServiceProviderStubPath();
        $projectPath = static::getServiceProviderProjectPath("EventServiceProvider.php");
        $this->replaceServiceProviderFile($stubPath , $projectPath);
    }

    // protected static function replaceAuthServiceProvider() : void
    // {
    //     $stubContent = File::get(static::getAuthServiceProviderStubPath());
    //     $passportMigrationsIgnoringPlaceholder = "--passport-migrations-ignoring--";
        
    //     if(PixelTenancyManager::doesItCorrectlySupportedTenancy())
    //     {
    //         $replace =  "Passport::ignoreMigrations();" ;
    //     }
        
    //     $stubContent = Str::replace($passportMigrationsIgnoringPlaceholder , $replace ?? "" , $stubContent);

    //     $finalProviderPath = static::getServiceProviderProjectPath("AuthServiceProvider.php");
    //     File::put($finalProviderPath , $stubContent);
    // }

    protected function replaceRouteServiceProvider() : void
    {
        $stubPath = static::getRouteServiceProviderStubPath();
        $projectPath = static::getServiceProviderProjectPath("RouteServiceProvider.php");
        $stubIdentifier = static::initStubIdentifier($stubPath , $projectPath);

        $stubIdentifier->callOnFileContent(function(string $fileContent)
        {
            $tenancyRoutesPlaceHolder = "--loading-tenant-routes--";
         
            $replace =  PixelTenancyManager::DoesItNeedTenantRoutes() ?
                        "PixelRouteManager::loadTenantRoutes();" :
                        ""; // an empty string needed to remove placeholder string
    
            return Str::replace($tenancyRoutesPlaceHolder , $replace , $fileContent);
        });

        $this->replaceStubFile($stubIdentifier); 
    }
    
}