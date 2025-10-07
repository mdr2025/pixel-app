<?php

namespace PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppInstallingManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

abstract class MigrationFileStubIdentifierFactory
{
    protected static array $instances = [];

    protected static ?string $tenantFolderCustomName = null;

    private function __construct(){}

    
    /**
     * gets the relevant path including file name with extension
     */
    abstract protected function getFilePackageMigrationRelevantPath() : string;
    
    /**
     *  file name without extension
     */
    abstract public function getFileName() : string;

    public static function Singleton() : MigrationFileStubIdentifierFactory
    {
        if(! isset( static::$instances[ static::class  ] ))
        {
            static::$instances[ static::class  ] = new static();
        }

        return static::$instances[ static::class  ];
    }

    public static function useTenantFolderCustomName(string $folderName) : void
    {
        static::$tenantFolderCustomName = $folderName;
    }    
    
    public function createCentralStubIdentifiers() : ?StubIdentifier
    {
        if($this->doesItNeedCentralStubReplacement())
        {
            $stubPath = $this->getPackageFilePath();
            $replacingPath = $this->getCentralFileProjectPath();
            return $this->initStubIdentidier($stubPath , $replacingPath);
        }
        return null;
    }


    public function createTenantStubIdentidier() : ?StubIdentifier
    {
        if($this->doesItNeedTenantStubReplacement())
        {
            $stubPath = $this->getPackageFilePath();
            $replacingPath = $this->getTenantFileProjectPath();
            return $this->initStubIdentidier($stubPath , $replacingPath);
        }

        return null;
    }

    protected function initStubIdentidier(string $stubPath , string $replacingPath)  : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $replacingPath);
    }

    protected function processRealPath(string $path)  :string
    {
        return realpath($path);
    }


    protected function getTenantFolderName()  :string
    {
        return static::$tenantFolderCustomName ?? "tenant";
    }

    public function getCentralFileProjectPath() : string
    {
        $path = $this->getProjectMigrationsFolderPath() 
                . "/" .
                $this->getFileName(). $this->getFileExtension();
                
        return $this->processRealPath($path);
    }
  
    public function getTenantFileProjectPath()  :string
    {
        $path = $this->getProjectMigrationsFolderPath() 
                . "/" .
                $this->getTenantFolderName()
                . "/" .
                $this->getFileName(). $this->getFileExtension();
        
        return $this->processRealPath($path);
    }

    public function getProjectMigrationsFolderPath() : string
    {
        return app_path("database/migrations");
    }

    public function getPackageFilePath() : string
    {
        $path = static::getPackageMigrationBasePath() 
                . "/" .
                $this->getFilePackageMigrationRelevantPath() ;

        return $this->processRealPath($path);
    }

    public function getFileExtension() : string
    {
        return ".php";
    }

    public static function getPackageMigrationBasePath() : string
    {
        return  __DIR__ . "/../MigrationStubs" ;
    }
 
    /**
     * in general migration files case ... it must be overritten to allow replacment in all cases and app types
     */
    protected function doesItNeedCentralStubReplacement() : bool
    {
        return $this->isInstallingForAdminPanel() || $this->isInstallingForMonolithApp() || $this->isInstallingForNormalApp();
    }

    protected function doesItNeedTenantStubReplacement() : bool
    {
        return $this->isInstallingForMonolithApp() || $this->isInstallingForTenantApp();
    }

    protected function initPixelAppInstallingManager() : PixelAppInstallingManager
    {
        return PixelAppInstallingManager::Singleton();
    }

    protected function isInstallingForNormalApp() : bool
    {
        return $this->initPixelAppInstallingManager()->isInstallingForNormalApp();
    }

    protected function isInstallingForAdminPanel() : bool
    {
        return $this->initPixelAppInstallingManager()->isInstallingForAdminPanel();
    }

    protected function isInstallingForTenantApp() : bool
    {
        return $this->initPixelAppInstallingManager()->isInstallingForTenantApp();
    }

    protected function isInstallingForMonolithApp() : bool
    {
        return $this->initPixelAppInstallingManager()->isInstallingForMonolithApp();
    }
    
}