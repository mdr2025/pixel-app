<?php

namespace PixelApp\Database\Seeders;

use Illuminate\Support\Facades\File;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PixelSeedersStubManager extends PixelAppStubsManager
{

    public function replaceSeederStubs() : void
    {
        $stubFiles = $this->scanAllSeederStubs();
        
        foreach($stubFiles as $stubPath)
        {
            if($this->shouldReplaceStub($stubPath))
            {
                $this->replaceStubFileAutomatically($stubPath);
            }
        }
    }
    
    protected function initSeederStubIdentifier(string $stubPath , string $replacingPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $replacingPath);
    }

    protected function doesItNeedTenantStubReplacement() : bool
    {
        return $this->isItMonolithApp() || $this->isItTenantApp();
    }

    protected function getPackageSeederStubFolderPath() : string
    {
        return __DIR__ . "/SeederStubs";
    }

    protected function scanAllSeederStubs() : array
    {
        $stubsPath = $this->getPackageSeederStubFolderPath();
        
        if(!is_dir($stubsPath))
        {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($stubsPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $stubFiles = [];
        foreach($iterator as $file)
        {
            if($file->isFile() && $file->getExtension() === 'php')
            {
                $stubFiles[] = $file->getPathname();
            }
        }
        
        return $stubFiles;
    }

    protected function shouldReplaceStub(string $stubPath) : bool
    {
        // Check if stub path contains "Tenant" keyword
        if(stripos($stubPath, 'Tenant') !== false)
        {
            return $this->doesItNeedTenantStubReplacement();
        }
        
        return true;
    }

    protected function replaceStubFileAutomatically(string $stubPath) : void
    {
        $namespace = $this->extractNamespaceFromStub($stubPath);
        if($namespace === null)
        {
            return;
        }
        
        $fileName = basename($stubPath);
        $replacingPath = $this->getProjectSeedersFilePathFromNamespace($namespace, $fileName);
        $stubIdentifier = $this->initSeederStubIdentifier($stubPath, $replacingPath);
        
        $this->replaceStubFile($stubIdentifier);
    }

    protected function extractNamespaceFromStub(string $stubPath) : ?string
    {
        if(!file_exists($stubPath))
        {
            return null;
        }

        $content = File::get($stubPath);
        
        if(preg_match('/namespace\s+([^;]+);/', $content, $matches))
        {
            return trim($matches[1]);
        }

        return null;
    }

    protected function convertNamespaceToDirectoryPath(string $namespace) : string
    {
        // Remove "Database\Seeders" prefix
        $namespace = preg_replace('/^Database\\\\Seeders\\\\?/', '', $namespace);
        
        // Convert namespace separators to directory separators
        $directoryPath = str_replace('\\', '/', $namespace);
        
        return $directoryPath;
    }

    protected function getProjectSeedersFilePathFromNamespace(string $namespace, string $fileName) : string
    {
        $directoryPath = $this->convertNamespaceToDirectoryPath($namespace);
        
        $fullPath = database_path("seeders");
        
        if(!empty($directoryPath))
        {
            $fullPath .= "/" . $directoryPath;
            // Ensure directory exists
            if(!File::isDirectory($fullPath))
            {
                File::makeDirectory($fullPath, 0755, true);
            }
        }
        
        return $fullPath . "/" . $fileName;
    }

    protected function isItTenantApp() : bool
    {
        return PixelTenancyManager::isItTenantApp();
    }

    protected function isItMonolithApp() : bool
    {
        return PixelTenancyManager::isItMonolithTenancyApp();
    }
}