<?php

namespace Database\Seeders;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseType::insert([
            [
                'name'=>'Full Asset Value',
                'category'=> 'assets',
            ],
            [
                'name'=>'Partial Asset Value',
                'category'=> 'assets',
            ],
        ]);
    }
}
