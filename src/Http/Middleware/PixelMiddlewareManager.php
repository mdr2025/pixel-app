<?php

namespace PixelApp\Http\Middleware;
 

class PixelMiddlewareManager
{

    protected static function initPixelMiddlewareStubsManager() : PixelMiddlewareStubsManager
    {
        return PixelMiddlewareStubsManager::Singleton();
    }

    public static function installPackageMiddlewareStubs() : void
    {
        static::initPixelMiddlewareStubsManager()->replacePixelAppMiddlewareStubs();
    }
    public static function getProjectMiddlewarePath() : string
    {
        return static::getProjectHttpPath() . "/Middleware";
    }

    public static function getProjectMiddlewareFilePath(string $fileName) : string
    {
        return static::getProjectMiddlewarePath() . "/" . $fileName;
    }

    public static function getProjectHttpPath() : string
    {
        return app_path("Http");
    }
    
    public static function getProjectHttpFilePath(string $fileName) : string
    {
        return static::getProjectHttpPath() . "/" . $fileName;
    }
}