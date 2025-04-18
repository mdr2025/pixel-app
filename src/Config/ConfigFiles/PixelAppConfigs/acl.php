<?php

use PixelApp\Models\SystemConfigurationModels\RoleModel;

$acl = [];
$highestRoleName = RoleModel::getHighestRoleName();
$lowestRoleName = RoleModel::getLowestRoleName();

$highestRolePermisions = [
                                ///////// system configurations /////////////
                                "read_sc-dropdown-lists",
                                "create_sc-dropdown-lists",
                                "edit_sc-dropdown-lists",
                                "delete_sc-dropdown-lists",
                                "read_sc-roles-and-permissions",
                                "create_sc-roles-and-permissions",
                                "edit_sc-roles-and-permissions",
                                "delete_sc-roles-and-permissions",
                                /////////Users Module//////////////
                                "read_umm-signup-list",
                                "edit_umm-signup-list",
                                "delete_umm-signup-list",
                                "approve_umm-signup-list",
                                "reject_umm-signup-list",
                                "re-verify_umm-signup-list",
                                "reverification_umm-signup-list",
                                "edit-email_umm-signup-list",
                                "read_umm-users-list",
                                "edit_umm-users-list",
                                "delete_umm-users-list",
                                //////////Profile///////////
                                "read_profile",
                                //////////company account///////////to review later //
                                // "read_company-account",
                                // "edit_company-account",
                                // "change-admin-email_company-account",
                                // "reset-data_company-account",
                                // "add-branch_company-account",
                                // "read-branch_company-account",
                                // "edit-branch_company-account"
                            ];


$lowestRolePermisions = ["read_profile"];

$acl["permissions"][$highestRoleName] = $highestRolePermisions;
$acl["permissions"][$lowestRoleName] = $lowestRolePermisions;
$acl["roles"] = [$highestRoleName , $lowestRoleName];
$acl["default_roles"] = [$highestRoleName ];

return $acl;
