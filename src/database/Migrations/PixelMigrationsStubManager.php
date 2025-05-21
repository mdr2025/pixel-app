<?php

namespace PixelApp\Database\Migrations;

use Exception;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\MigrationFileStubIdentifierFactory;

class PixelMigrationsStubManager extends PixelAppStubsManager
{

    
    public function replaceMigrationStubs() : void
    {
        foreach($this->getMigrationFileStubIdentifierFactoryClasses() as $factoryClass)
        {
            $factory = $this->initMigrationsFileStubIdentifierFactory($factoryClass);

            if($centralStub = $factory->createCentralStubIdentifiers())
            {
                $this->replaceStubFile($centralStub);
            }

            if($tenantStub = $factory->createTenantStubIdentidier())
            {
                $this->replaceStubFile($tenantStub);
            }
        }
    }

   
    protected function initMigrationsFileStubIdentifierFactory(string $factoryClass) : MigrationFileStubIdentifierFactory
    {
        return $factoryClass::Singleton();
    }

    protected function getMigrationFileStubIdentifierFactoryClasses() : array
    {
        return [

        ];
    }

}