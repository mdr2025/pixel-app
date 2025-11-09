 <?php

$permissions = [
                                "read_dashboard",
                                "read_tasks",
                                // ========================================
                                // SYSTEM CONFIGURATION (SC) MODULE
                                // ========================================
                                "read_sc-dropdown-lists",
                                "create_sc-dropdown-lists",
                                "edit_sc-dropdown-lists",
                                "delete_sc-dropdown-lists",
                                // Dropdown Lists - Branches
                                "read_sc-dropdown-lists-branches",
                                "create_sc-dropdown-lists-branches",
                                "edit_sc-dropdown-lists-branches",
                                "delete_sc-dropdown-lists-branches",
                                
                                // Dropdown Lists - Departments
                                "read_sc-dropdown-lists-departments",
                                "create_sc-dropdown-lists-departments",
                                "edit_sc-dropdown-lists-departments",
                                "delete_sc-dropdown-lists-departments",

                                // Dropdown Lists - Branch Teams
                                "read_sc-dropdown-lists-branches-teams",
                                "create_sc-dropdown-lists-branches-teams",
                                // Company Account Management
                                "change-admin-email_company-account",
                                // Roles and Permissions
                                "read_sc-roles-and-permissions",
                                "create_sc-roles-and-permissions",
                                "edit_sc-roles-and-permissions",
                                "delete_sc-roles-and-permissions",
                                // ========================================
                                // USER MANAGEMENT MODULE (UMM)
                                // ========================================
                                // Signup List Management
                                "read_umm-signup-list",
                                "edit_umm-signup-list",
                                "delete_umm-signup-list",
                                "approve_umm-signup-list",
                                "reject_umm-signup-list",
                                "re-verify_umm-signup-list",
                                "reverification_umm-signup-list",
                                "edit-email_umm-signup-list",
                                // Users List Management
                                "read_umm-users-list",
                                "edit_umm-users-list",
                                "delete_umm-users-list",
                                //////////Profile///////////
                                "read_profile",
                                "edit_profile",
                                /////////Signature/////////
                                "read_Signature",
                                "create_Signature",
                                "edit_Signature",
                                "delete_Signature",
                ];
 
$superAdminPermissions = array_merge($permissions, [
    // Company Account Management (Super Admin Only)
    "reset-data_company-account",
    "read_company-account",
    "edit_company-account",
    //////////company account///////////to review later //
    "read_company-account",
    "edit_company-account",
    "change-admin-email_company-account",
    "reset-data_company-account",
]);

return [
    'permissions' => [
        "Super_Admin" => $superAdminPermissions,
        "Default_User" =>  $permissions,
    ],
    "default_roles" => [
                            "Super Admin",
                            "Default User",
                       ],
    "highestRole" => "Super Admin",
    "lowestRole" => "Default User"
];

