<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use PixelApp\Config\PixelConfigManager;
use PixelApp\Database\PixelDatabaseManager;
use PixelApp\Models\PixelModelManager;

class PermissionsSeeder extends Seeder
{

    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getRoleModelClass();
    }

    protected function getHighestRoleConfigKey() : string
    {
        $modelClass= $this->getRoleModeClass();
        return $modelClass::getHighestRoleName();
    }
    
   
    protected function setDefaultPermissionsStringArray() : array
    {
        return  PixelConfigManager::getPixelAppPackagePermissions();
    }
    
    protected function getAllPermissionStrings()  :array
    {
        return  $this->setDefaultPermissionsStringArray()[ $this->getHighestRoleConfigKey() ] 
        ??
        throw new Exception("There is no permission to seed in acl config file ");
    }

    protected function getPermissionsInsertableDataArray() : array
    {

        $permissions = $this->getAllPermissionStrings();
        $data = [];

        foreach ($permissions as $permission)
        {
            $data[] = ['guard_name' => 'api', 'name' => $permission];
        }
        return $data;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(! empty( $data = $this->getPermissionsInsertableDataArray() ))
        {
            $tableName = $this->getTableName();

            DB::table( $tableName )->insert($data);
        }
    }

    protected function getTableName()  :string
    {
        return PixelDatabaseManager::getPermissionsTableName();
    }

}
