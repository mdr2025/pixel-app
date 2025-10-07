<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Spatie\Permission\Models\Role;
use Throwable;

class RolesSeeder extends Seeder
{
    protected array $defaultPermissions = [];

    
    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getRoleModelClass();
    }

    protected function createRoleOb(string $role )  : ?Role
    {
        try {

            /*  @var Role $roleOb  */
            $modelClass = $this->getRoleModeClass();
            $roleOb = $modelClass::create(['guard_name'=>'api','name' => $role , "default" => 1 ,  'editable' => 1 , 'deletable' => 0, 'status' => 1]);

        }catch (Throwable $exception)
        {
            Log::error( $exception->getMessage() );
        }
        return $roleOb;
    }

    protected function getDefaultRoleDefaultPermissions(string $role) : array
    {
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
