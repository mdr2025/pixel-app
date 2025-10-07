<?php

namespace PixelApp\Database\Seeders;

use Database\Seeders\CompanyResetSeeder;
use Exception;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\MigrationFileStubIdentifierFactory;

class PixelSeedersStubManager extends PixelAppStubsManager
{

    public function replaceSeederStubs() : void
    {
        $this->replaceCompanyResetSeederStub();
        $this->replaceDatabaseSeederStub();
        $this->replaceTenantDatabaseSeederStub();
    }
    
    protected function initSeederStubIdentifier(string $stubPath) : StubIdentifier
    {
        $replacingPath = $this->getProjectSeedersFolderPath();
        return StubIdentifier::create($stubPath , $replacingPath);
    }

    protected function replaceCompanyResetSeederStub() : void
    {
        $stubPath =  $this->getCompanyResetSeederStubPath();
        $stubIdentifier = $this->initSeederStubIdentifier($stubPath );
        
        $this->replaceStubFile($stubIdentifier);
    }

    protected function replaceDatabaseSeederStub() : void
    {
        $stubPath =  $this->getDatabaseSeederStubPath();
        $stubIdentifier = $this->initSeederStubIdentifier($stubPath );
        
        $this->replaceStubFile($stubIdentifier);
    }

    
    protected function doesItNeedTenantStubReplacement() : bool
    {
        return $this->isItMonolithApp() || $this->isItTenantApp();
    }

    protected function replaceTenantDatabaseSeederStub() : void
    {
        if($this->doesItNeedTenantStubReplacement())
        {
            $stubPath =  $this->getTenantDatabaseSeederStubPath();
            $stubIdentifier = $this->initSeederStubIdentifier($stubPath );
            
            $this->replaceStubFile($stubIdentifier);
        }
    }

    protected function processRealPath(string $path)  :string
    {
        return realpath($path);
    }

    protected function getPackageSeederStubFolderPath() : string
    {
        return __DIR__ . "/SeederStubs";
    }

    protected function getStubRelaPath(string $stubFileName) : string
    {
        $path = $this->getPackageSeederStubFolderPath() . "/" . $stubFileName;
        return $this->processRealPath($path);
    }

    protected function getCompanyResetSeederStubPath() : string
    {
        return $this->getStubRelaPath( "CompanyResetSeeder.php" );
    }

    protected function getDatabaseSeederStubPath() : string
    {
        return $this->getStubRelaPath( "DatabaseSeeder.php" );
    }

    protected function getTenantDatabaseSeederStubPath() : string
    {
        return $this->getStubRelaPath( "TenantDatabaseSeeder.php" );
    }

    protected function getProjectSeedersFolderPath() : string
    {
        return app_path("database/seeders");
    }

    protected function isItTenantApp() : bool
    {
        return PixelTenancyManager::isItTenantApp();
    }

    protected function isItMonolithApp() : bool
    {
        return PixelTenancyManager::isItMonolithTenancyApp();
    }
}