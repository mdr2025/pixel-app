<?php

namespace PixelApp\Database\Seeders\UserModuleSeeders;

 
use Illuminate\Database\Seeder;
use PixelApp\Database\Factories\UserModule\AcceptedUserFactory;
use PixelApp\Models\UsersModule\UserProfile;

class AcceptedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = new AcceptedUserFactory();
        $factory->count(200)
            ->has( UserProfile::factory()->withRoleState()->withDepartmentState()->count(1) )
            ->create();
    }
}
