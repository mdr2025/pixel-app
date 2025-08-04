<?php

namespace PixelApp\Routes;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier; 
class PixelRouteStubsManager extends PixelAppStubsManager
{

    public function replacePixelAppRouteStubs() : void
    {
        static::replaceApiRouteStubFile();
        static::replaceWebRouteStubFile();

        if(static::DoesItNeedTenantRoutesInstalling())
        {
            static::replaceTenantRouteStubFile();
        }
    }
    
    protected static function initPixelRoutesInstallingManager() : PixelRoutesInstallingManager
    {
        return PixelRoutesInstallingManager::Singlton();
    }

    public static function DoesItNeedTenantRoutesInstalling() : bool
    {
        return static::initPixelRoutesInstallingManager()->DoesItNeedTenantRoutesInstalling();
    }
    
    protected static function getPackageRouteStubsPath() : string
    {
        return realpath(__DIR__ . "/RouteFileStubs");
    }
  
    protected static function getPackageRouteFileStubPath(string $stubFileName) : string
    {
        return static::getPackageRouteStubsPath() . "/" . $stubFileName;
    }

    protected static function initStubIdentifier(string $stubPath , string $projectRouteFilePath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $projectRouteFilePath);
    }

    protected function replaceStub(string $stubPath , string $projectRouteFilePath)
    { 
        $stubIdentifier = static::initStubIdentifier($stubPath , $projectRouteFilePath);
        $this->replaceStubFile($stubIdentifier);
    }

    protected function replaceTenantRouteStubFile() : void
    {
        $stubPath = static::getPackageRouteFileStubPath("tenant.php");
        $projectRouteFilePath = base_path("routes/tenant.php");
        static::replaceStub($stubPath , $projectRouteFilePath);
    }

    protected function replaceWebRouteStubFile() : void
    {
        $stubPath = static::getPackageRouteFileStubPath("web.php");
        $projectRouteFilePath = base_path("routes/web.php"); 
        static::replaceStub($stubPath , $projectRouteFilePath);
    }

    protected function replaceApiRouteStubFile() : void
    {
        $stubPath = static::getPackageRouteFileStubPath("api.php");
        $projectRouteFilePath = base_path("routes/api.php"); 
        static::replaceStub($stubPath , $projectRouteFilePath);
    }

}
// separated admin panel = app without tenancy + company auth server  => needs routes without central domains because it is on a single domain
// separated tenant app => needs routes with central with central routes and company auth client != monolith
// tenant app with admin panel => needs routes with central routes and company auth server = monolith
// app without tenancy => deosn\'t need central routes or company auth