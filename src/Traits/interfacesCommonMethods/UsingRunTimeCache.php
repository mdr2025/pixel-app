<?php

namespace PixelApp\Traits\interfacesCommonMethods;

use PixelApp\Exceptions\JsonException; 
use Illuminate\Database\Eloquent\Model;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;
use RuntimeCaching\RuntimeCacheTypes\ParentModelRuntimeCache;

trait UsingRunTimeCache
{

    protected ?ParentModelRuntimeCache $parentModelRunTimeCache = null;

    protected function initParentModelRunTimeCache() : ParentModelRuntimeCache
    {
        if(!$this->parentModelRunTimeCache)
        {
            $this->parentModelRunTimeCache = ParentModelRuntimeCache::singleton();
        }
        return $this->parentModelRunTimeCache;
    }

    protected function restartParentModelRunTimeCache() : void
    {
        $this->parentModelRunTimeCache = null;
    }

    /**
     * @param string $relationship
     * @return Model | null
     * This Method Will Query A new PArent When If It Is Not Found In The Run Time Cahce
     */
    protected function queryNewRelationship(string $relationship) : Model | null
    {
        return $this->{$relationship}()->first();
    }

    protected function getRelationshipForeignKeyValue(string $relationship) : int | null
    {
        return $this->{ $this->{$relationship}()->getForeignKeyName() };
    }

    protected function getRunTimeCacheKey(string $parentClass , int | null $primaryKey) : string
    {
        return $parentClass. "-" . $primaryKey;
    }

    /**
     * @param string $parentClass
     * @param string $relationship ,
     * @return Model|null
     *
     * This Method Is Called When A Relationship Is Called
     */
    protected function getParentFromRunTimeCache( string $relationship ,  string $parentClass) : Model | null
    {
        $key = $this->getRunTimeCacheKey($parentClass , $this->getRelationshipForeignKeyValue($relationship));
        return $this->initParentModelRunTimeCache()->get($key);
    }

    protected function getCachedParent(string $relationship , string $parentClass) : Model | null
    {
        $parentOb = $this->getParentFromRunTimeCache($relationship , $parentClass);
        $this->restartParentModelRunTimeCache();
        return $parentOb;
    }

    protected function getParentRelationshipValue(string $relationship ) : Model | null | bool
    {
        /** @var NeedToAccessParentRelationships $this */

        /**
         * $key  = A Relationship Name
         * $parentRelationships[$key] = Related Parent 's Qualified Class
         */
        $parentRelatedClass =  $this->getParentRelationshipsDetails()[$relationship] ;
        return $this->getCachedParent( $relationship , $parentRelatedClass ) ?? $this->queryNewRelationship($relationship);

    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function setParentToRunTimeCache() : void
    {
        if(ParentModelRuntimeCache::NeededFromChildes($this) )
        {
            $key = $this->getRunTimeCacheKey( static::class  , $this->getKey() );
            $this->initParentModelRunTimeCache()->add($key , $this);
            $this->restartParentModelRunTimeCache();
        }
    }
}
