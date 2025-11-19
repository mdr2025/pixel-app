<?php

namespace PixelApp\Policies\IndependentGates;

use AuthorizationManagement\independentGateManagement\IndependentGates\IndependentGate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Models\UsersModule\PixelUser;

class SuperAdminIndependentGates extends IndependentGate
{

    protected function getLoggedUser(): Authenticatable|PixelUser|null
    {
        return Auth::user();
    }
    protected function isLoggedUserDefaultSuperAdmin() : bool
    {
        $user = $this->getLoggedUser();
        if($user instanceof PixelUser)
        {
            return $user->isCreatedAsDefaultSuperAdmin();
        }

        return Str::lower($user?->role?->name) == RoleModel::getHighestRoleName();
    }

    protected function defineSuperAdminGate(): void
    {
        Gate::define("resetCompanyData", function ()
        {
            return $this->isLoggedUserDefaultSuperAdmin();
            //return $this->permissionExaminer->addPermissionToCheck("reset-data_company-account")->hasPermissionsOrFail();
        });
    }

    public function define(): void
    {
        $this->defineSuperAdminGate();
    }
}
