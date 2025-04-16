<?php

namespace PixelApp\Models;

use PixelApp\Traits\interfacesCommonMethods\HasUUIDMethods;
use PixelApp\Traits\interfacesCommonMethods\MustUploadModelFilesMethods;
use PixelApp\Traits\interfacesCommonMethods\UsingRunTimeCache;
use CRUDServices\FilesOperationsHandlers\FilePathsRetrievingHandler\FileFullPathsHandler;
use CRUDServices\FilesOperationsHandlers\FilesHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;
use PixelApp\Traits\DataSyncHelperMethods;
use ReflectionClass;
use RuntimeCaching\RuntimeCacheTypes\ParentModelRuntimeCache; 
use Statistics\Interfaces\ModelInterfaces\StatisticsProviderModel;


class PixelBaseModel extends Model implements StatisticsProviderModel
{
    use UsingRunTimeCache, MustUploadModelFilesMethods, HasUUIDMethods , DataSyncHelperMethods;
    public static $snakeAttributes = false;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->generateUUID();
    }
    protected static function booted()
    {
        static::retrieved(function ($model) {
            $model->setParentToRunTimeCache();
        });
        static::created(function ($model) {
            $model->setParentToRunTimeCache();
        });
    }
       /**
     * Only queryable in admin panel or monolith app (in central app aprt)
     */
    protected function newBaseQueryBuilder()
    {
        if($this instanceof OnlyAdminPanelQueryable && !PixelTenancyManager::isItAdminPanelApp())
        {
            dd("Can't execute any query on " . static::class . " model in this app type ");
        }

        parent::newBaseQueryBuilder();
    }
    
    static public function getTableTitle(string $tableName): string
    {
        $title = Str::title($tableName);
        return Str::replace("_", " ", $title);
    }
    
    public function getStatisticDateColumnName(): string
    {
        return "created_at";
    }

    public function getAttribute($key)
    {
        if (ParentModelRuntimeCache::NeedToAccessParentRelationships($this) && array_key_exists($key, $this->getParentRelationshipsDetails()))
        {
            return $this->getParentRelationshipValue($key);
        }

        return parent::getAttribute($key);
    }
  
    public function toArray()
    {
        if (FileFullPathsHandler::MustUploadModelFiles($this)) {
            return $this->getPathCompletedAttrsArray();
        }
        return parent::toArray();
    }
  
    
    public static function getTableName(): string
    {
        $reflection = new ReflectionClass(static::class);
        $model = $reflection->newInstanceWithoutConstructor(); 
        return $model->getTable();
    }

    //NOTE: this not required in our case should disabled
    /**
     * Retrieve the model for a bound value.
     *
     * @param  \Illuminate\Database\Eloquent\Model|Illuminate\Database\Eloquent\Relations\Relation  $query
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($this->getKeyType() == 'int' && !is_numeric($value)) {
            throw new Exception("A route parameter must has int typed value to use in model route paramter bindings !");
        }

        /**
         * Here you can if the type is string and validate it to avoid query injection because laravel doesn't check the string typed value
         */
        return parent::resolveRouteBindingQuery($query, $value, $field);
    }
}
