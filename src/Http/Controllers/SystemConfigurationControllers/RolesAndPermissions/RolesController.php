<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\RolesAndPermissions;

use PixelApp\Exceptions\JsonException;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions\PermissionsResource;
use PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions\RoleShowResource;
use PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions\RolesListResource;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleDeletingService;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleStoringService;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices\RoleDisablingSwitcher;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices\RoleInfoUpdatingService;
use PixelApp\Services\PixelServiceManager;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    protected $filterable = [
        'name',
        'status'
    ];

    public function index()
    { 
        BasePolicy::check('read', Role::class);
        $data = RoleModel::orderBy('disabled', 'asc')->orderBy('default', 'desc')->get();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(RolesListResource::class);
        return $resourceClass::collection($data); 
    }

    public function list()
    {
        BasePolicy::check('read', Role::class);
        $data = RoleModel::nonDefaultRole()->get();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(RolesListResource::class);
        return $resourceClass::collection($data); 
    }

    function show($id)
    {
        BasePolicy::check('read', Role::class); 
        $role = RoleModel::findOrFail($id);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(RoleShowResource::class);
        return new $resourceClass($role);
    }

    public function store(Request $request): JsonResponse
    {
        BasePolicy::check('create', Role::class); 
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleStoringService::class);
        return (new $service())->create($request);
    }

    public function update($id, Request $request): JsonResponse
    {
        BasePolicy::check('edit', Role::class); 
        $role = RoleModel::nonDefaultRole()->find($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleInfoUpdatingService::class);
        return (new $service($role))->change($request);
    }


    //Make It Enabled or Disabled

    /**
     * @throws JsonException
     * @throws \Exception
     */
    public function switchRole(Request $request, $id): JsonResponse
    {
        BasePolicy::check('edit', Role::class); 
        $role = RoleModel::findOrFail($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleDisablingSwitcher::class);
        return (new $service($role))->change($request);
    }

    public function destroy($id): JsonResponse
    {
        BasePolicy::check('delete', Role::class); 
        $role = RoleModel::findOrFail($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleDeletingService::class);
        return (new $service($role))->delete(true);
    }

    public function allPermission()
    {
        BasePolicy::check('read', Role::class); 

        $permissions = Permission::pluck('name')->get();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(PermissionsResource::class);
        return $resourceClass::collection($permissions); 
    }
}
