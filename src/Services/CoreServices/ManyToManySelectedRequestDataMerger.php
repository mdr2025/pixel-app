<?php

namespace PixelApp\Services\CoreServices;

use PixelApp\Exceptions\JsonException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ManyToManySelectedRequestDataMerger
{
    protected static ?Request $request = null;
    protected static  string  $relationshipName;
    protected static Model | string | null  $relationshipLocalKeyName = null;
    protected static ?string $requestKeyName = null;
    protected static string $relationshipClass;


    public static function setRelationshipLocalKeyName(string $relationshipLocalKeyName): void
    {
        self::$relationshipLocalKeyName = $relationshipLocalKeyName;
    }

    /**
     * @return void
     */
    public static function prepareRelationshipLocalKeyName(): void
    {
        if (!self::$relationshipLocalKeyName) {
            /** @var Model $model  */
            $model = new self::$relationshipClass;
            $relationshipLocalKeyName = $model->getKeyName();
            self::setRelationshipLocalKeyName($relationshipLocalKeyName);
        }
    }

    protected static function getRelationshipAllIds(): array
    {
        self::prepareRelationshipLocalKeyName();
        return self::$relationshipClass::pluck(self::$relationshipLocalKeyName)->toArray();
    }
    public static function processAllIDsValueNeed(array $relationshipRequestData): array
    {
        if (empty($relationshipRequestData)) {
            return [];
        }

        if (Arr::first($relationshipRequestData) != 'all') {
            return array_filter($relationshipRequestData, 'is_numeric');
        }

        return self::getRelationshipAllIds();
    }

    protected static function getRelationshipRequestData(): array
    {
        return self::$request->input(self::$requestKeyName, []);
    }
    protected static function getRelationshipRequestNewData(): array
    {
        $relationshipRequestData = self::getRelationshipRequestData();
        $relationshipRequestData = self::processAllIDsValueNeed($relationshipRequestData);
        return [self::$relationshipName => $relationshipRequestData];
    }
    protected static function mergeRequestNewData(): void
    {
        self::$request->merge(self::getRelationshipRequestNewData());
    }

    public static function setRequest(): Request
    {
        if (!self::$request) {
            self::$request = request();
        }
        return self::$request;
    }


    protected static function setRelationshipClass(string $relationshipModelClass): void
    {
        if (! is_subclass_of($relationshipModelClass, Model::class)) {
            throw new JsonException("$relationshipModelClass is not a Model typed class !");
        }

        self::$relationshipClass = $relationshipModelClass;
    }

    /**
     * @param string|null $requestKeyName
     * @return void
     */
    public static function setRequestKeyName(?string $requestKeyName = null): void
    {
        if (!$requestKeyName) {
            $requestKeyName = self::$relationshipName;
        }
        self::$requestKeyName = $requestKeyName;
    }

    /**
     * @param string $relationshipName
     */
    public static function setRelationshipName(string $relationshipName): void
    {
        self::$relationshipName = $relationshipName;
    }

    public static function mergeData(string $relationshipName, string $relationshipModelClass,  ?string $requestKeyName = null): void
    {
        self::setRelationshipName($relationshipName);
        self::setRequestKeyName($requestKeyName);
        self::setRelationshipClass($relationshipModelClass);
        self::setRequest();
        self::mergeRequestNewData();
    }
}
