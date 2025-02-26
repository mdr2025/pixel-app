<?php

namespace Database\Seeders\TenantSeeders;


use App\Models\WorkSector\SystemConfigurationModels\AssetsCategory;
use Illuminate\Database\Seeder;

class AssetsCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetsCategory::factory()->count(200)->create();
    }
}
