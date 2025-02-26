<?php

namespace Database\Seeders\TenantSeeders;
 
use Illuminate\Database\Seeder;

class InsurancTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsurancType::factory()->count(200)->create();
    }
}
