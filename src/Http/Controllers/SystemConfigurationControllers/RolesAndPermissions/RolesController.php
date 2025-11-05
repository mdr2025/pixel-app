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
use PixelApp\Models\PixelModelManager;
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

        $modelClass = $this->getRoleModeClass();
        $data = $modelClass::orderBy('activate_button', 'asc')->orderBy('default', 'desc')->get();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(RolesListResource::class);
        return $resourceClass::collection($data); 
    }

    public function listAllRoles(Request $request)
    {
        BasePolicy::check('read', Role::class);
        
        $modelClass = $this->getRoleModeClass();
        $includeAdmin = $request->boolean('filter.has_admin', false);

        // Query Builder Pattern أفضل
        $roles = $modelClass::query()
                            ->activeRole()
                            ->when(!$includeAdmin, function ($query) {
                                $highestRoleName = RoleModel::getHighestRoleName();
                                return $query->where('name', '!=', $highestRoleName);
                            })
                            ->orderBy('default', 'desc')
                            ->orderBy('name', 'asc')
                            ->get();
        
        return RolesListResource::collection($roles);
    }

    public function listDefaultRoles()
    {
        BasePolicy::check('read', Role::class);

        $modelClass = $this->getRoleModeClass();
        $data = $modelClass::defaultRole()->get();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(RolesListResource::class);
        return $resourceClass::collection($data); 
    }

    
    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getRoleModelClass();
    }

    protected function findOrFailById(int $id) : RoleModel
    {
        $modelClass = $this->getRoleModeClass();
        return $modelClass::findOrFail($id);
    }

    public function show($id)
    {
        BasePolicy::check('read', Role::class); 

        $role = $this->findOrFailById($id);
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

        $modelClass = $this->getRoleModeClass();

        $role = $modelClass::nonDefaultRole()->where("id" , $id)->firstOrFail();
        
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

        $role = $this->findOrFailById($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleDisablingSwitcher::class);
        return (new $service($role))->change($request);
    }

    public function destroy($id): JsonResponse
    {
        BasePolicy::check('delete', Role::class); 

        $role = $this->findOrFailById($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(RoleDeletingService::class);
        return (new $service($role))->delete(true);
    }

    public function allPermission()
    {
        BasePolicy::check('read', Permission::class); 

        $permissionModelClass = PixelModelManager::getPermissionModelClass();
        $permissions = $permissionModelClass::pluck('name');
        
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(PermissionsResource::class);
        return $resourceClass::collection($permissions); 
    }
}
