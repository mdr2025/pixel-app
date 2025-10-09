<?php

namespace PixelApp\Database\Seeders;

use Database\Seeders\CompanyResetSeeder;
use Exception;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class PixelSeedersStubManager extends PixelAppStubsManager
{

    public function replaceSeederStubs() : void
    {
        $this->replaceCompanyResetSeederStub();
        $this->replaceDatabaseSeederStub();
        $this->replaceTenantDatabaseSeederStub();
    }
    
    protected function initSeederStubIdentifier(string $stubPath , string $replacingPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $replacingPath);
    }

    protected function replaceCompanyResetSeederStub() : void
    {
        $stubPath =  $this->getCompanyResetSeederStubPath();
        $replacingPath = $this->getCompanyResetSeederProjectPath();
        $stubIdentifier = $this->initSeederStubIdentifier($stubPath , $replacingPath );
        
        $this->replaceStubFile($stubIdentifier);
    }

    protected function replaceDatabaseSeederStub() : void
    {
        $stubPath =  $this->getDatabaseSeederStubPath();
        $replacingPath = $this->getDatabaseSeederProjectPath(); 
        $stubIdentifier = $this->initSeederStubIdentifier($stubPath , $replacingPath );
        
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
            $replacingPath = $this->getTenantDatabaseSeederProjectPath();
            $stubIdentifier = $this->initSeederStubIdentifier($stubPath , $replacingPath );
            
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

    protected function getStubRealPath(string $stubFileName) : string
    {
        $path = $this->getPackageSeederStubFolderPath() . "/" . $stubFileName;
        return $this->processRealPath($path);
    }

    protected function getCompanyResetSeederFileName() : string
    {
        return "CompanyResetSeeder.php";
    }

    protected function getCompanyResetSeederStubPath() : string
    {
        $fileName = $this->getCompanyResetSeederFileName();
        return $this->getStubRealPath( $fileName );
    }
    
    protected function getCompanyResetSeederProjectPath() : string
    {
        $fileName = $this->getCompanyResetSeederFileName();
        return $this->getProjectSeedersFilePath( $fileName );
    }

    protected function getDatabaseSeederFileName() : string
    {
        return "DatabaseSeeder.php";
    }

    protected function getDatabaseSeederProjectPath() :  string
    {
        $fileName = $this->getDatabaseSeederFileName();
        return $this->getProjectSeedersFilePath( $fileName );
    }

    protected function getDatabaseSeederStubPath() : string
    {
        $fileName = $this->getDatabaseSeederFileName();
        return $this->getStubRealPath( $fileName );
    }

    protected function getTenantDatabaseSeederFileName() : string
    {
        return "TenantDatabaseSeeder.php";
    }

    protected function getTenantDatabaseSeederProjectPath() :  string
    {
        $fileName = $this->getTenantDatabaseSeederFileName();
        return $this->getProjectSeedersFilePath( $fileName );
    }

    protected function getTenantDatabaseSeederStubPath() : string
    {
        $fileName = $this->getTenantDatabaseSeederFileName();
        return $this->getStubRealPath( $fileName );
    }

    protected function getProjectSeedersFilePath(string $fileName) : string
    {
        return database_path("seeders/" . $fileName);
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