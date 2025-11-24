<?php

namespace Database\Seeders\TenantResettingSeeders;

use Illuminate\Database\Seeder;

/**
 * This seeder can be used when a tenant app company want to execute a partial reset data
 * NOT ON NEW TENANT DATABASE CONFIGURING PROCESS
 */
class TenantCompanyPartialResettingSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //because of partial resetting .... there is no default seeder can be used here
        //run the project 's own seeder (normal app , central app , admin panel seeders)
        $this->call([
        
        ]);
    }
}
