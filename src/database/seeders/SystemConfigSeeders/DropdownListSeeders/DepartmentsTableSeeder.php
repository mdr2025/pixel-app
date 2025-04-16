<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Models\SystemConfigurationModels\Department;

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
            Department::create([ "name" => $department , "status" => 1 ]);
        }
    }
}
