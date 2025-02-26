<?php

namespace Database\Seeders\TenantSeeders;

use Illuminate\Database\Seeder;

class ClientVisitExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientVisitExpense::factory()->count(200)->create();
    }
}
