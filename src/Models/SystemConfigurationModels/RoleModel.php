<?php

namespace PixelApp\Models\SystemConfigurationModels;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use Spatie\Permission\Models\Role;

class RoleModel extends Role
{
    use SoftDeletes;

    protected $casts = [
        'disabled' => 'boolean',
        'editable' => 'boolean',
        'deletable' => 'boolean',
        'status' => 'boolean',
        'default' => 'boolean'
    ];

    public function scopeActiveRole()
    {
        return $this->where('disabled', 0);
    }
    public function scopeDefaultRole()
    {
        return $this->where('default', 1);
    }
    
    public function scopeNonDefaultRole()
    {
        return $this->where('default', 0);
    }
    public function isActive() : bool
    {
        return $this->disabled == 0;
    }
    public function isDefault() : bool
    {
        return (bool) $this->default;
    }
    
    public static function getHighestRoleName() : string
    {
        return config( static::getHighestRoleConfigKeyName(), "Super Admin");
    }

    public static function findHighestRole() : ?RoleModel
    {
        return static::where("name" , static::getHighestRoleName())->first();
    }
    
    public static function getLowestRoleName() : string
    {
        return config( static::getLowestRoleConfigKeyName(), "Default User");
    }

    public static function findLowestRole() : ?RoleModel
    {
        return static::where("name" , static::getLowestRoleName())->first();
    }
    
    protected static function getLowestRoleConfigKeyName() : string
    {
        return PixelConfigManager::getPixelAppPackageACLConfigKeyName() . ".lowestRole"; 
    }

    protected static function getHighestRoleConfigKeyName() : string
    {
        return PixelConfigManager::getPixelAppPackageACLConfigKeyName() . ".highestRole"; 
    }

    /**
     * @throws Exception
     */
    public static function throwDeletedDefaultRolesException() : void
    {
        throw new Exception("There is no default role in acl config file .... They must not be deleted from the file !") ;
    }

    public static function getDefaultRolesOrFail() : array
    {
         $defaultRoles = PixelConfigManager::getPixelAppPackageACLConfigs()["default_roles"];
        
        if(!$defaultRoles)
        {
            static::throwDeletedDefaultRolesException();
        }
        
        return $defaultRoles;
    }

    public function user()
    {
        return $this->hasMany(PixelModelManager::getUserModelClass() , 'role_id');
    }
}
