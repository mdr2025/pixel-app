<?php

namespace PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubIdentifierFactories\ComapnyAccountMigrationStubsIdentifierFactories;

use PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubFactories\CompanyMigrationStubIdentifierFactory;

class CompanyAdditionalPackagesMigrationStubIdentifiersFactory extends CompanyMigrationStubIdentifierFactory
{ 

    /**
     * in general migration files case ... it must be overritten to allow replacment in all cases and app types
     */
    protected function doesItNeedCentralStubReplacement() : bool
    {
        return $this->isInstallingForAdminPanel() || $this->isInstallingForMonolithApp() ;
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
        return "2023_06_24_110920_create_company_additional_packages_table";
    } 
   
}