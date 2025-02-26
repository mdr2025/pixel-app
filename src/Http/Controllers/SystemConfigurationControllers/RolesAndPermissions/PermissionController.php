<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\RolesAndPermissions;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Policies\SystemConfigurationPolicies\RolesAndPermissionsPolicies\RolesAndPermissionsPolicies;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function store(Request $request)
    {
        BasePolicy::check('create', Permission::class);
        (new RolesAndPermissionsPolicies())->create();
        $data = [];

        foreach ($request->data as $permission) {
            if ($permission["isCrud"] == 1) {
                $data[] = [
                    'name' => "read_{$permission['name']}",
                    "guard_name" => "api",
                ];
                $data[] = [
                    'name' => "create_{$permission['name']}",
                    "guard_name" => "api",
                ];
                $data[] = [
                    'name' => "edit_{$permission['name']}",
                    "guard_name" => "api",
                ];
                $data[] = [
                    'name' => "delete_{$permission['name']}",
                    "guard_name" => "api",
                ];
            } else {
                $data[] = [
                    'name' => $permission['name'],
                    "guard_name" => "api",
                ];
            }
        }
        try {
            DB::beginTransaction();
            $permissions = Permission::insert($data);
            $permissionIds = Permission::latest()->pluck('id')->take(count($data));
            foreach ($permissionIds as $id) {
                DB::table('role_has_permissions')->insert([
                    "role_id" => 1,
                    "permission_id" => $id,
                ]);
            }
            $permissionNames = [];

            foreach ($data as $permission) {
                $permissionNames[] = $permission['name'];
            }
            db::commit();
            return response()->json($permissionNames, 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
}
