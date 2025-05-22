<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{

    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(RoleModel::class);
    }

    protected function getHighestRoleConfigKey() : string
    {
        $modelClass= $this->getRoleModeClass();
        return $modelClass::getHighestRoleName();
    }
    
    protected function getAllPermissionStrings()  :array
    {
        return  config('acl.permissions.' . $this->getHighestRoleConfigKey());
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
            DB::table("permissions")->insert($data);
        }
    }
}
