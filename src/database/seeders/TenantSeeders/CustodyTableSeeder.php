<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\PersonalSector\PersonalTransactions\Inflow\Custody;
use Illuminate\Database\Seeder;

class CustodyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Custody::factory()->count(200)->create();
    }
}
