<?php

namespace PixelApp\Models\SystemConfigurationModels;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Models\PixelBaseModel;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $default
 */
class Branch extends PixelBaseModel
{
    use HasFactory;
    protected $table = "branches";
    const ROUTE_PARAMETER_NAME = "branch";
    protected $fillable = [ "name",  "status"  , "default"];

    protected $casts = [
        'status' => 'boolean',
        'default' => 'boolean',
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeDefault($query)
    {
        $query->where('default', 1);
    }

    public function isActive()  :bool
    {
        return (bool) $this->status;
    }
    public function isDefault() : bool
    {
        return (bool) $this->default;
    }
    public static function getHeadquarterBranchName() : string
    {
        return "Headquarter";
    }
    public static function findHeadquarter() : self
    {
        return static::where("name" , static::getHeadquarterBranchName())->first();
    }

    public function isHeadquarter() : bool
    {
        return (bool) $this->name == $this::getHeadquarterBranchName();
    }
}
