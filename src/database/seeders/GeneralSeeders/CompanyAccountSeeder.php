<?php

namespace PixelApp\Database\Seeders\GeneralSeeders;

use Illuminate\Database\Seeder;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\BranchesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\CurrenciesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\DepartmentsTableSeeder;
use PixelApp\Models\CompanyModule\CompanyAccountModels\CompanyAccount;
use PixelApp\Models\PixelModelManager;

class CompanyAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(! $this->doesAppNeedsCentralDBPermissions())
        {
            return ;
        }

        $companyAccount = $this->getCompanyAccountModelClass()::create(
                              [
                                    'name' => "Company", 
                                    'sector' => "Sector",
                                    'country_id' => 1,
                                    'logo' => null, 
                                    'address' => "",
                                    'email' => "Company@mail.com",
                                    'mobile' => "00000000000"
                              ]
                          );

    }

    protected function getCompanyAccountModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyAccount::class);
    }
    protected function doesAppNeedsCentralDBPermissions() : bool
    {
        return !PixelAppBootingManager::isBootingForTenantApp();
    }
}
