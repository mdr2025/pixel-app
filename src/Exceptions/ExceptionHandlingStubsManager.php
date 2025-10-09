<?php

namespace PixelApp\Exceptions;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

class ExceptionHandlingStubsManager extends PixelAppStubsManager
{
 
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

    protected function getExceptionProjectFilePath(string $fileName) : string
    {
        return app_path("Exceptions" . DIRECTORY_SEPARATOR . $fileName);
    }

    protected function getExceptionHandlerFileName()  :string
    {
        return "Handler.php";
    }

    protected function getExceptionHandlerStubPath() : string
    {
        $fileName = $this->getExceptionHandlerFileName();
        return $this->proceccRealPath( __DIR__ . DIRECTORY_SEPARATOR . "ExceptionStubs" . DIRECTORY_SEPARATOR  . $fileName );
    }

    protected function getExceptionHandlerProjectPath() : string
    {
        $fileName = $this->getExceptionHandlerFileName();
        return $this->getExceptionProjectFilePath($fileName);
    }

    protected function initExceptionHandlerStubIdentifier() : StubIdentifier
    {
        $stubPath = $this->getExceptionHandlerStubPath();
        $replacingPath = $this->getExceptionHandlerProjectPath();

        return StubIdentifier::create($stubPath , $replacingPath);
    }

    
}