<?php

namespace PixelApp\Http\Middleware;
 
use Illuminate\Foundation\Configuration\Middleware;

class PixelMiddlewareManager
{

    public static function handlePixelAppMiddlewares(Middleware $middleware) : void
    {
        $middleware->throttleApi();
        $middleware->remove(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->alias([
                    'auth' =>  \PixelApp\Http\Middleware\AliassedMiddlewares\Authenticate::class,
                    'cors' => \PixelApp\Http\Middleware\AliassedMiddlewares\Cors::class,
                    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
                    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
                    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
                    'reqLimit' => \PixelApp\Http\Middleware\AliassedMiddlewares\RateLimitingMiddleware::class,
                    'protectFile' => \PixelApp\Http\Middleware\AliassedMiddlewares\ProtectFilesMidlleware::class,
                    'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class
        ]);
    }

    protected static function initPixelMiddlewareStubsManager() : PixelMiddlewareStubsManager
    {
        return PixelMiddlewareStubsManager::Singleton();
    }

    public static function installPackageMiddlewareStubs() : void
    {
        return ; //must remove the replacing step later
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