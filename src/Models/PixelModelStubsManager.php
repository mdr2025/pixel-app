<?php

namespace PixelApp\Models;
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier; 

class PixelModelStubsManager extends PixelAppStubsManager
{

    public function replacePixelAppModelStubs() : void
    {
        $this->replaceUserStub();  
    }
    
    protected function getPixeModelStubsPath() : string
    {
        return __DIR__ . "/LaravelModelStubs";
    }
    
    protected function getPixelModelStubFilePath(string $fileName) : string
    {
        return $this->getPixeModelStubsPath() . "/" . $fileName;
    }
 
    protected function getUserStubPath() : string
    {
        return $this->getPixelModelStubFilePath("User.php");
    }
 
    protected function getProjectModelsPath() : string
    {
        return PixelModelManager::getProjectModelsPath();
    } 
    
    protected function getProjectUserModelPath() : string
    {
        return static::getProjectModelsPath() . "/User.php";
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
   
    protected function replaceUserStub() : void
    {
        $stubPath = $this->getUserStubPath()  ;
        $newPath = $this->getProjectUserModelPath("Kernel.php") ; 
        $this->replaceStub($stubPath , $newPath);
    }

}