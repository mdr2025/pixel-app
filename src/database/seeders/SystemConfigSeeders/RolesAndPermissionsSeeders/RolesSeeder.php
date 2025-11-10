<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Spatie\Permission\Models\Role;
use Throwable;
use Illuminate\Support\Str;

class RolesSeeder extends Seeder
{
    protected array $defaultPermissions = [];

    
    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getRoleModelClass();
    }

    protected function getHighestRoleName() : string
    {
        return $this->getRoleModeClass()::getHighestRoleName();
    }

    protected function getLowestRoleName() : string
    {
        return $this->getRoleModeClass()::getLowestRoleName();
    }

    protected function getEdittingButtonValueForRole(string $roleName) : int
    {
        return $roleName == $this->getHighestRoleName() ? 0 : 1;
    }

    protected function createRoleOb(string $role )  : ?Role
    {
        try {

            /*  @var Role $roleOb  */
            $modelClass = $this->getRoleModeClass();
            
            $roleOb = $modelClass::create([
                'guard_name'=>'api','name' => $role ,
                "default" => 1 ,  'status' => 1,
                'edit_button' => $this->getEdittingButtonValueForRole($role),
                'delete_button' => 0, 'activate_button' => 0 ,
            ]);

            return $roleOb;
            
        }catch (Throwable $exception)
        {
            Log::error( $exception->getMessage() );

            throw $exception;
        }
    }

    protected function getDefaultRoleDefaultPermissions(string $role) : array
    {
        $role = Str::replace(" " , "_" , $role );
        return $this->defaultPermissions[ $role ];
    }
  
    protected function getDefaultRoleStringArray() : array
    {
        return RoleModel::getDefaultRolesOrFail();
    }

    protected function setDefaultPermissionsStringArray() : void
    {
        $this->defaultPermissions =  PixelConfigManager::getPixelAppPackagePermissions();
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setDefaultPermissionsStringArray();

        //Looping on all roles and setting their permissions
        foreach ($this->getDefaultRoleStringArray() as $role)
         {
             $roleOb = $this->createRoleOb($role);
             $roleOb?->syncPermissions( $this->getDefaultRoleDefaultPermissions($role) );
         }
    }


}
