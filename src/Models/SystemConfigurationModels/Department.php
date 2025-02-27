<?php

namespace PixelApp\Models\SystemConfigurationModels;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use PixelApp\Models\PixelBaseModel;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $status
 * @property int $default
 */
class Department extends PixelBaseModel implements NeededFromChildes , NeedToAccessParentRelationships
{
    use HasFactory;
    protected $table = "departments";
    const ROUTE_PARAMETER_NAME = "department";
    protected $fillable = [ "name","parent_id","status"  ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
        'default' => 'boolean',
        'parent_id' => 'integer'
    ];
    public function scopeWhereItIsParent(Builder | Relation $query)
    {
        $query->whereNull("parent_id"  );
    }
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
    
    public function getParentRelationshipsDetails(): array
    {
        return ["parent" => Department::class];
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
    public function childDepartment() : HasOne
    {
        return $this->hasOne(Department::class, 'parent_id');
    }
}
