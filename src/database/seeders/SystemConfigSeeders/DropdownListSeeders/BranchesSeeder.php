<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Models\SystemConfigurationModels\Branch;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([ "name" => Branch::getHeadquarterBranchName() ,"status" => 1 , "default" => 1 ]);
    }
}
