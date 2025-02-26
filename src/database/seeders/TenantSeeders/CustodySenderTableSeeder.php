<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\SystemConfigurationModels\CustodySender;
use Illuminate\Database\Seeder;

class CustodySenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustodySender::factory()->count(200)->create();
    }
}
