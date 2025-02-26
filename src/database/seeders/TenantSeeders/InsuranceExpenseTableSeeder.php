<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\FinanceModule\TaxesAndInsurances\InsuranceExpense;
use Illuminate\Database\Seeder;

class InsuranceExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsuranceExpense::factory()->count(200)->create();
    }
}
