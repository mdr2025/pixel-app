<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\SystemConfigurationModels\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( Department::getDefaultDepartments() as $department )
        {
            Department::create([ "name" => $department , "default" => 1 ]);
        }
    }
}
