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
        $replacingPath = $this->getProjectConsoleFolderPath();
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

    protected function getStubRelaPath(string $stubFileName) : string
    {
        $path = $this->getPackageSeederStubFolderPath() . "/" . $stubFileName;
        return $this->processRealPath($path);
    }

    protected function getKernelStubPath() : string
    {
        return $this->getStubRelaPath( "Kernel.php" );
    }

    protected function getProjectConsoleFolderPath() : string
    {
        return app_path("Console");
    }
}