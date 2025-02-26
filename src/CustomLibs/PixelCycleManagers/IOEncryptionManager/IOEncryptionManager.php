<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\IOEncryptionManager;


use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use PixelApp\CustomLibs\IOEncryptionHandler\IOEncryptionHandler;
use PixelApp\CustomLibs\IOEncryptionHandler\LaravelCustomComponents\ResponseCustomFactory as ResponseFactory;

class IOEncryptionManager
{

    public static function registerObjects($app) : void
    {
        $app->singleton(ResponseFactoryContract::class, function ($app) {
            return new ResponseFactory($app[ViewFactoryContract::class], $app['redirect']);
        });
    }

    public static function bootFuncs() : void
    {
        IOEncryptionHandler::decryptRequestInputs();
    }
}