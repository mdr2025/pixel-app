<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\SystemConfigurationModels\Branch;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([ "name" => Branch::getHeadquarterBranchName() , "default" => 1 ]);
    }
}
