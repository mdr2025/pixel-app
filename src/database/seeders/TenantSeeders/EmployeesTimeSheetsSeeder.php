<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\HRModule\EmployeeTimeSheet;
use Illuminate\Database\Seeder;

class EmployeesTimeSheetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        EmployeeTimeSheet::factory()->count(50)->create();
    }
}
