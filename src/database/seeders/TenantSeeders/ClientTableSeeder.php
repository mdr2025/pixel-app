<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\WorkSector\ClientsModule\Client;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::factory()->count(200)->create();
    }
}
