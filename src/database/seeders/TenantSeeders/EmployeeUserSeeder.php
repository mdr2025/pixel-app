<?php

namespace Database\Seeders\TenantSeeders;
use App\Models\WorkSector\UsersModule\UserProfile;
use Database\Factories\User\EmployeeUserFactory;
use Illuminate\Database\Seeder;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = new EmployeeUserFactory();
        $factory->count(200)
            ->has( UserProfile::factory()->withRoleState()->withDepartmentState()->count(1) )
            ->create();
    }
}
