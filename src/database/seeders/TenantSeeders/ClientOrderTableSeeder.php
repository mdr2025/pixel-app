<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\ClientsModule\ClientOrder;
use Illuminate\Database\Seeder;

class ClientOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientOrder::factory()->count(200)->create();
    }
}
