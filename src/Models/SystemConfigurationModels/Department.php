<?php

namespace PixelApp\Models\SystemConfigurationModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Models\PixelBaseModel;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $status
 * @property int $default
 */
class Department extends PixelBaseModel implements NeededFromChildes
{
    use HasFactory;
    protected $table = "departments";
    const ROUTE_PARAMETER_NAME = "department";
    protected $fillable = [ "name","parent_id","status" , "default"];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
        'default' => 'boolean',
        'parent_id' => 'integer'
    ];

    public function isActive() : bool
    {
        return (bool) $this->status;
    }

    public static function getTeamManagementDepartmentName() : string
    {
        return "Management Team" ;
    }
    public static function findTeamManagementDepartment() : self
    {
        return static::where("name" , static::getTeamManagementDepartmentName())->first();
    }
    public static function getDefaultDepartments() : array
    {
        return [
            static::getTeamManagementDepartmentName() , "HR" , "Accounting"
        ];
    }
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
}
