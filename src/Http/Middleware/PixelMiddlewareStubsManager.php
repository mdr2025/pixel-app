<?php

namespace PixelApp\Http\Middleware;
 
use PixelApp\CustomLibs\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelAppStubsManager\StubIdentifiers\StubIdentifier; 

class PixelMiddlewareStubsManager extends PixelAppStubsManager
{

    public function replacePixelAppMiddlewareStubs() : void
    { 
        $this->replaceKernel();
        $this->replaceAuthenticateMiddleware();
    }
    
    protected function getPixelMiddlewareStubsPath() : string
    {
        return __DIR__ . "/PixelMiddlewareStubs";
    }
    
    protected function getPixelMiddlewareStubFilePath(string $fileName) : string
    {
        return $this->getPixelMiddlewareStubsPath() . "/" . $fileName;
    }

    protected function getAuthenticateMiddlewareStubPath() : string
    {
        return $this->getPixelMiddlewareStubFilePath("LaravelMiddlewareStubs/Authenticate.php")  ;
    }

    protected function getKernalStubPath() : string
    {
        return $this->getPixelMiddlewareStubFilePath("Kernel.php");
    }
 
    protected function getProjectMiddlewarePath() : string
    {
        return PixelMiddlewareManager::getProjectMiddlewarePath();
    }

    protected function getProjectMiddlewareFilePath(string $fileName) : string
    {
        return PixelMiddlewareManager::getProjectMiddlewareFilePath($fileName);
    }

    protected function getProjectHttpPath() : string
    {
        return PixelMiddlewareManager::getProjectHttpPath();
    }
    protected function getProjectHttpFilePath(string $fileName) : string
    {
        return PixelMiddlewareManager::getProjectHttpFilePath($fileName); 
    }

    protected function initStubIdentifier(string $stubPath , string $newPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $newPath);
    }

    protected function replaceStub(string $stubPath , string $newPath) : void
    {
        $stubIdentifier = $this->initStubIdentifier($stubPath , $newPath);
        $this->replaceStubFile($stubIdentifier)  ;
    } 
 
    protected function replaceAuthenticateMiddleware() : void
    {
        $stubPath = $this->getAuthenticateMiddlewareStubPath();
        $newPath = $this->getProjectMiddlewareFilePath("Authenticate.php") ;
        $this->replaceStub($stubPath , $newPath);
    }

    protected function replaceKernel() : void
    {
        $stubPath = $this->getKernalStubPath()  ;
        $newPath = $this->getProjectHttpFilePath("Kernel.php") ; 
        $this->replaceStub($stubPath , $newPath);
    }

}