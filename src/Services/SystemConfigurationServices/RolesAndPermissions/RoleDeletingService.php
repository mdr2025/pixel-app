<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\RoleUsersManager;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchAllRoleUsersToDefaultRole;
use PixelApp\Traits\TransactionLogging;

class RoleDeletingService
{
    use TransactionLogging;

    private RoleModel $role;
    private RoleUsersManager $usersManager;
    protected array $DefaultRoles;

    public function __construct(RoleModel $role)
    {
        $this->role = $role;
        $this->DefaultRoles = $this->getDefaultRoleStringArray();
        $this->usersManager = new SwitchAllRoleUsersToDefaultRole($this->role);
    }

    protected function getDefaultRoleStringArray() : array
    {
        return RoleModel::getDefaultRolesOrFail();
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function switchUsersToDefaultRole(): bool
    {
        return $this->usersManager->switchRoleUsers();
    }

    protected function IsDefaultRole(): bool
    {
        return in_array($this->role->name, $this->DefaultRoles);
    }

    /**
     * @return true
     * @throws Exception
     */
    private function deleteSoftly(): bool
    {
        $this->role->deleted_at = now();
        $this->role->status = 1;
        
        if (!$this->role->save())
        {
            throw new Exception("Failed To Delete Role");
        }
        
    }

    /**
     * @return true
     * @throws Exception
     */
    private function forcedDelete(): void
    {
        if (!$this->role->forceDelete())
        {
            throw new Exception("Failed To Delete Role");
        }
        
    }

    public function delete(bool $forcedDelete = false): JsonResponse
    {
         return $this->surroundWithTransaction(function () use ($forcedDelete) {

            if ($this->IsDefaultRole())
            {
                throw new Exception("Can't Delete Any Default Role Or Its Permissions");
            }
            
            if ($this->role->user()->activeUsers()->count() > 0)
            {
                throw new Exception("Role can not be deleted as it has assigned users");
            }

            
            //If It Fails To Switch Users .... An Exception Will Be Thrown And The Deleting Operation Will Stop
            $this->switchUsersToDefaultRole();

            //We Don't Check If Deleting Methods Gets true or False ... Because They Didn't Throw Any Exception That Means The Deleting Is Successful
            if ($forcedDelete) {
                $this->forcedDelete();
            } else {
                $this->deleteSoftly();
            }


            return Response::success([], ["Role Has Been Deleted Successfully"]);
        });
    }
}
