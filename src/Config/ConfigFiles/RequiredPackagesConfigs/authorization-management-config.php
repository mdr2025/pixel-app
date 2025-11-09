<?php

use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Models\UsersModule\Signature;
use PixelApp\Models\UsersModule\UserProfile;
use PixelApp\Policies\AuthenticationPolicies\CompanyModulePolicies\CompanyModulePolicy;
use PixelApp\Policies\IndependentGates\SuperAdminIndependentGates;
use PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies\AreaPolicy;
use PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies\BranchPolicy;
use PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies\DepartmentPolicy;
use PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies\DropDownListPolicy;
use PixelApp\Policies\SystemConfigurationPolicies\RolesAndPermissionsPolicies\RolesAndPermissionsPolicies;
use PixelApp\Policies\UserAccountPolicies\SignaturePolicy;
use PixelApp\Policies\UserAccountPolicies\UserProfilePolicy;
use PixelApp\Policies\UserManagementPolicies\UsersModulePolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return [

    "policies" => [
 
        /** System Configuration Policies */
        Role::class => RolesAndPermissionsPolicies::class,
        RoleModel::class => RolesAndPermissionsPolicies::class,
        Permission::class => RolesAndPermissionsPolicies::class,

        /** DropDownList Policies */
        Department::class                 => DepartmentPolicy::class,
        Area::class                       => AreaPolicy::class,
        Branch::class                     => BranchPolicy::class,
        City::class                       => DropDownListPolicy::class,
        Currency::class                   => DropDownListPolicy::class,
       
        /** Authentication Policies */
        TenantCompany::class                => CompanyModulePolicy::class,
        UserProfile::class                  => UsersModulePolicy::class,
        PixelUser::class                    => UsersModulePolicy::class,
        UserProfile::class                  => UserProfilePolicy::class,
        Signature::class                    => SignaturePolicy::class
    ],
    "independent_gates" => [
        SuperAdminIndependentGates::class
    ]
];
