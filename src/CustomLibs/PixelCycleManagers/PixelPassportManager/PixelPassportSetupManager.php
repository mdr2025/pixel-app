<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use PixelApp\CustomLibs\Tenancy\PixelTenancy;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\TenantCompany;

class PixelPassportSetupManager
{
    protected static $instance = null;
    protected ?Command $outputCommand = null;
    

    protected function __construct(){}

    public static function Singleton() : self
    {
        if(!  static::$instance )
        {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public function setOutputCommand( Command $outputCommand)  : self
    {
        $this->outputCommand = $outputCommand;
        return $this;
    }

    public function getOutoutCommand() : ?Command
    {
        return $this->outputCommand;
    }

    protected function seedPassportClients() : self
    {
        Artisan::call('pixel-passport:create-client ', [ '--name' => config('app.name').' Personal Access Client']);
        $this->getOutoutCommand()?->info(Artisan::output());
        
        return $this;
    }

    protected function generatePassportKeys() : self
    {
        Artisan::call('passport:keys', ['--force' => 'true']);
        $this->getOutoutCommand()?->info(Artisan::output());

        return $this;
    }

    protected function generateAppEncryptionKey() : self
    {
        if(!env('APP_KEY'))
        {
            Artisan::call("key:generate");

            $this->getOutoutCommand()?->info(Artisan::output());
        }

        return $this;
    }

    protected function doesHaveTokensInBothSide()
    {
        return PixelPassportManager::doesHaveTokensInBothSide();
    }

    protected function doesHaveOnlyTenantTokens()
    {
        return PixelPassportManager::doesHaveOnlyTenantTokens();
    }

    protected function doesHaveOnlyCentralTokens() : bool
    {
        return PixelPassportManager::doesHaveOnlyCentralTokens();    
    }

    protected function fetchTenantsByAdminPanel() : Collection
    {
        return PixelTenancyManager::fetchTenantsByAdminPanel();
    }

    protected function fetchTenantsFromCentralSide() : Collection
    {
        return PixelTenancyManager::fetchTenantsFromCentralSide();
    }

    protected function getForeignKeyDependentDeletingStatus() : bool
    {
        return PixelPassportManager::getForeignKeyDependentDeletingStatus();
    }
    
    protected function initPixelPassportTokensManager() : PixelPassportTokensManager
    {
        return PixelPassportTokensManager::Singleton();
    }

    protected function truncatePassportTables() : void
    {
        //no else case .... only truncating in cases we determined 
        $pixelPassportTokensManager = $this->initPixelPassportTokensManager();

        //deleting all clients
        $pixelPassportTokensManager->truncateClientTable();


        /**
         * - if we can delete the clients ... the all things related to parent client will be deleted automatically
         * - But if the foreign key deleting cascading is removed ... we need to delete clients , personal clients , tokens , refresh tokens manually
         * - This case is only will happen when the developer want to remove this foreign key deleting cascade manually 
         *  so it also must inform PixelPassportManager to change this deleting opertion behavior
         */
        if(!$this->getForeignKeyDependentDeletingStatus())
        {
            $pixelPassportTokensManager->truncatePersonalClientTable()
                                       ->truncateAccessTokensTable()
                                       ->truncateRefreshTokensTable();
        }

    }

    /**
     * For tenant app type
     */
    protected function truncateTenantTables(Collection $tenants) : void
    {
        foreach($tenants as $tenant)
        {
            if($tenant instanceof TenantCompany)
            {
                $tenant->run(function()
                {
                    $this->truncatePassportTables();
                });
                
            }
        }
    }

    
    /**
     * For normal app type
     */
    protected function truncateCentralTables() : void
    {
        $this->truncatePassportTables();
    }

    /**
     * For monolith app type
     */
    protected function truncateTablesInBothSide() : void
    {
        $this->truncateCentralTables();

        $tenants = $this->fetchTenantsFromCentralSide();
        $this->truncateTenantTables($tenants);
    }
    

    protected function HandlePassportTablesTruncating() : void
    {
        if($this->doesHaveOnlyCentralTokens())
        {
            $this->truncateCentralTables();

        }elseif($this->doesHaveOnlyTenantTokens())
        {

            $tenants = $this->fetchTenantsByAdminPanel();
            $this->truncateTenantTables($tenants);

        }elseif($this->doesHaveTokensInBothSide())
        {

            $this->truncateTablesInBothSide();

        }
    }

    protected function clearCache() : self
    { 
        Artisan::call("optimize:clear"); 

        $this->getOutoutCommand()?->info(Artisan::output());

        return $this;
    }

    public function setupPassport(bool $firstTimeSetup = true, Command $outputCommand)  : void
    {
        if(!app()->runningInConsole())
        {
            throw new Exception("These functinality must be executed in console .... Please use passport configuring command !");
        }
        
        $this->setOutputCommand($outputCommand)
             ->clearCache();

            if(!$firstTimeSetup)
            {
                $this->HandlePassportTablesTruncating();
            }


             $this->generateAppEncryptionKey()
                  ->generatePassportKeys()
                  ->seedPassportClients();
        
 
        
    }

}