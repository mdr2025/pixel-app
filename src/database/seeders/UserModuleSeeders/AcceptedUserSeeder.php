<?php

namespace PixelApp\Database\Seeders\UserModuleSeeders;

 
use Illuminate\Database\Seeder;
use PixelApp\Database\Factories\UserModule\AcceptedUserFactory;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\UserProfile;

class AcceptedUserSeeder extends Seeder
{

    protected function getUserProfileModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(UserProfile::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = new AcceptedUserFactory();
        $factory->count(200)
                ->withRoleState()->withDepartmentState()
                ->has( $this->getUserProfileModelClass()::factory()->count(1) )
                ->create();
    }
}
