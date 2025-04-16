<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{

    protected function getHighestRoleConfigKey() : string
    {
        return Str::replace(" "  , "_" , RoleModel::getHighestRoleName());
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
