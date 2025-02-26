<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\SystemConfigurationModels\TaxType;
use Illuminate\Database\Seeder;

class TaxTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxType::insert([[
            "name" => "Sales Taxes",
            "percentage" =>0,
            "status"=>1,
            "type" =>"add",
            "created_at" => now(),
            "updated_at"=>now()
        ]]);
    }
}
