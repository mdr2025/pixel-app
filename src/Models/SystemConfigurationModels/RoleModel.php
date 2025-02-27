<?php

namespace PixelApp\Models\SystemConfigurationModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Models\PixelModelManager;
use Spatie\Permission\Models\Role;

class RoleModel extends Role
{
    use SoftDeletes;

    protected $casts = [
        'disabled' => 'boolean',
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
        return "Super Admin";
    }
    public static function findHighestRole() : ?RoleModel
    {
        return static::where("name" , static::getHighestRoleName())->first();
    }
    public static function getLowestRoleName() : string
    {
        return "Default User";
    }
    public static function findLowestRole() : ?RoleModel
    {
        return static::where("name" , static::getLowestRoleName())->first();
    }
    public function user()
    {
        return $this->hasMany(PixelModelManager::getUserModelClass() , 'role_id');
    }
}
