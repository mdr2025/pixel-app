<?php

namespace Database\Seeders\TenantSeeders;


use App\Models\PersonalSector\PersonalTransactions\Inflow\Bonus;
use Illuminate\Database\Seeder;

class BonusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bonus::factory()->count(200)->create();
    }
}
