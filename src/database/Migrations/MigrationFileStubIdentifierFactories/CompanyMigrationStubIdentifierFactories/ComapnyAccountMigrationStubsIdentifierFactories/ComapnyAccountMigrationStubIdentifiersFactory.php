<?php

namespace PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubIdentifierFactories\ComapnyAccountMigrationStubsIdentifierFactories;

use PixelApp\Database\Migrations\MigrationFileStubIdentifierFactories\CompanyMigrationStubFactories\CompanyMigrationStubIdentifierFactory;

class ComapnyAccountMigrationStubIdentifiersFactory extends CompanyMigrationStubIdentifierFactory
{ 

    protected function getFilePackageMigrationRelevantPath() : string
    {
        return "CompanyMigrations/ComapnyAccountMigrations" 
                . $this->getFileName() . $this->getFileExtension() ;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "2013_04_10_214651_create_company_account_table";
    } 
   
}