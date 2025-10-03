<?php

namespace PixelApp\Exceptions;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

class ExceptionHandlingStubsManager extends PixelAppStubsManager
{

    public static ?ExceptionHandlingStubsManager $instance = null;

    public static function Singleton() : static
    {
        if(!static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function installExceptionStubs() : void
    {
        $this->replaceExceptionHandlerStub();
    }

    protected function replaceExceptionHandlerStub() : void
    {
        $stubIdentifier = $this->initExceptionHandlerStubIdentifier();
        $this->replaceStubFile($stubIdentifier);
    }

    protected function proceccRealPath(string $path) : string
    {
        return realpath($path);
    }

    protected function getExceptionProjectPath() : string
    {
        return app_path("Exceptions");
    }

    protected function getExceptionHandlerStubPath() : string
    {
        return $this->proceccRealPath( __DIR__ . DIRECTORY_SEPARATOR . "ExceptionStubs/Handler.php" );
    }

    protected function initExceptionHandlerStubIdentifier() : StubIdentifier
    {
        $stubPath = $this->getExceptionHandlerStubPath();
        $replacingPath = $this->getExceptionProjectPath();

        return StubIdentifier::create($stubPath , $replacingPath);
    }

    
}