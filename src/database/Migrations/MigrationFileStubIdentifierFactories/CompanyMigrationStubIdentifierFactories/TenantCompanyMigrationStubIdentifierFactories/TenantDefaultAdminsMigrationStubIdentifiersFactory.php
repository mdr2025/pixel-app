<?php

namespace PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubIdentifierFactories\ComapnyAccountMigrationStubsIdentifierFactories;

use PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubFactories\CompanyMigrationStubIdentifierFactory;

class TenantDefaultAdminsMigrationStubIdentifiersFactory extends CompanyMigrationStubIdentifierFactory
{ 

    /**
     * in general migration files case ... it must be overritten to allow replacment in all cases and app types
     */
    protected function doesItNeedCentralStubReplacement() : bool
    {
        return $this->isItAdminPanelApp() || $this->isItMonolithApp() ;
    }

    protected function getFilePackageMigrationRelevantPath() : string
    {
        return "CompanyMigrations/TenantCompanyMigrations" 
                . $this->getFileName() . $this->getFileExtension() ;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "2024_01_19_121839_create_tenant_default_admins_table";
    } 
   
}