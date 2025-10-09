<?php

namespace PixelApp\Console;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

class PixelConsoleStubManager extends PixelAppStubsManager
{

    public function installConsoleObjects() : void
    {
        $stubIdentifier = $this->initKernelStubIdentifier();
        
        $this->replaceStubFile($stubIdentifier);
    }
    
    protected function initKernelStubIdentifier() : StubIdentifier
    {
        $stubPath =  $this->getKernelStubPath();
        $replacingPath = $this->getKernelProjectConsolePath();
        return StubIdentifier::create($stubPath , $replacingPath);
    }
     
    protected function processRealPath(string $path)  :string
    {
        return realpath($path);
    }

    protected function getPackageSeederStubFolderPath() : string
    {
        return __DIR__ . "/ConsoleStubs";
    }

    protected function getStubRealPath(string $stubFileName) : string
    {
        $path = $this->getPackageSeederStubFolderPath() . "/" . $stubFileName;
        return $this->processRealPath($path);
    }

    protected function getKernelFileName() : string
    {
        return "Kernel.php";
    }

    protected function getKernelStubPath() : string
    {
        $fileName = $this->getKernelFileName();
        return $this->getStubRealPath( $fileName );
    }

    protected function getKernelProjectConsolePath() : string
    {
        $fileName = $this->getKernelFileName();
        return $this->getProjectConsoleFilePath($fileName);
    }

    protected function getProjectConsoleFilePath(string $fileName) : string
    {
        return app_path("Console/" . $fileName);
    }
}