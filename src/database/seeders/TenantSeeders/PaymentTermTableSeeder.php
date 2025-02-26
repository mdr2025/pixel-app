<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\SystemConfigurationModels\PaymentTerm;
use Illuminate\Database\Seeder;

class PaymentTermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTerm::factory()->count(200)->create();
    }
}
