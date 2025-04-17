<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders;

use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

class RolesSeeder extends Seeder
{
    protected array $defaultPermissions = [];
    protected function createRoleOb(string $role )  : ?Role
    {
        try {

            /*  @var Role $roleOb  */
            $roleOb = RoleModel::create(['guard_name'=>'api','name' => $role , "default" => 1 ,  'editable' => 1 , 'deletable' => 0, 'status' => 1]);

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
        return config('acl.default_roles');
    }
    protected function setDefaultPermissionsStringArray() : void
    {
        $this->defaultPermissions =  config('acl.permissions');
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
