<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\VendorsModule\Vendors\Vendor;
use Illuminate\Database\Seeder;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::factory()->count(200)->create();
    }
}
