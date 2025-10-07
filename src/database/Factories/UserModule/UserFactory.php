<?php

namespace PixelApp\Database\Factories\UserModule;

use PixelApp\Models\UsersModule\PixelUser as User;
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\SystemConfigurationModels\RoleModel;

abstract class UserFactory extends Factory
{

    protected $model = User::class;
    
    protected array $RoleIDS = [];
    protected int $RolesCount = 1;
    protected array $DepartmentIDS = [];
    protected int $DepartmentsCount = 1;

    protected function getAltModelOrBase(string $model) : string
    {
        return PixelModelManager::getUserModelClass();
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $first_name  = $this->faker->name();
        $last_name = $this->faker->name();
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            "name" => $first_name . " " . $last_name,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(22442244),
            'mobile' => $this->faker->unique()->phoneNumber(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'verification_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
  
 
    protected function setRoleIDS()
    {
        $modelClass = PixelModelManager::getRoleModelClass();
        $this->RoleIDS = $modelClass::pluck("id")->toArray();
        $this->RolesCount = count($this->RoleIDS);
    }

    public function withRoleState() : Factory
    {
        $this->setRoleIDS();
        return $this->state(function(array $attributes){
            return [
                'role_id' => $this->RoleIDS[rand(0 , $this->RolesCount)] ?? null
            ];
        });
    }

    protected function setDepartmentIDS()  : Factory
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(Department::class);
        $this->DepartmentIDS = $modelClass::pluck("id")->toArray();
        $this->DepartmentsCount = count($this->DepartmentIDS);
        return $this;
    }
    public function withDepartmentState()
    {
        $this->setDepartmentIDS();
        return $this->state(function(array $attributes){
            return [
                'department_id' => $this->DepartmentIDS[rand(0 , $this->DepartmentsCount)] ?? null
            ];
        });
    }
}
