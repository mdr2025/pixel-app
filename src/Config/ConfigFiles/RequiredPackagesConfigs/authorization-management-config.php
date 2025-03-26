<?php

use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Models\UsersModule\UserProfile;
use PixelApp\Policies\AuthenticationPolicies\CompanyModulePolicies\CompanyModulePolicy;
use PixelApp\Policies\AuthenticationPolicies\UsersModulePolicies\UsersModulePolicy;
use PixelApp\Policies\IndependentGates\SuperAdminIndependentGates;
use PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies\DropDownListPolicy;
use PixelApp\Policies\SystemConfigurationPolicies\RolesAndPermissionsPolicies\RolesAndPermissionsPolicies;
use PixelApp\Policies\UserManagementPolicies\UserProfilePolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return [

    "policies" => [
 
        /** System Configuration Policies */
        Role::class => RolesAndPermissionsPolicies::class,
        Permission::class => RolesAndPermissionsPolicies::class,

        /** DropDownList Policies */
        Department::class => DropDownListPolicy::class,
        Branch::class => DropDownListPolicy::class,
        Area::class => DropDownListPolicy::class,
       
        /** Authentication Policies */
        TenantCompany::class                  => CompanyModulePolicy::class,
        UserProfile::class     => UserProfilePolicy::class,
        PixelUser::class                     => UsersModulePolicy::class,
    ],
    "independent_gates" => [
        SuperAdminIndependentGates::class
    ]
];
