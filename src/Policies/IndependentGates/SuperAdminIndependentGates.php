<?php

namespace PixelApp\Policies\IndependentGates;

use AuthorizationManagement\independentGateManagement\IndependentGates\IndependentGate;
use Illuminate\Support\Facades\Gate;

class SuperAdminIndependentGates extends IndependentGate
{

    protected function defineSuperAdminGate(): void
    {
        Gate::define("resetCompanyData", function () {
            return strtolower(auth()->user()?->role?->name) === 'super admin';
            //return $this->permissionExaminer->addPermissionToCheck("reset-data_company-account")->hasPermissionsOrFail();
        });
    }

    public function define(): void
    {
        $this->defineSuperAdminGate();
    }
}
