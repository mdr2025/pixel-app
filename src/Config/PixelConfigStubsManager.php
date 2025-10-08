<?php

namespace PixelApp\Config;
 
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppInstallingManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;


class PixelConfigStubsManager extends PixelAppStubsManager
{ 
 
    public function getTenancyConfigFileIdentifierClass() : string
    {
        return PixelConfigManager::getTenancyConfigFileIdentifierClass(); 
    }
 
    public function getReplacableFileConfigIdentifierClasses() : array
    {
        return PixelConfigManager::getReplacableFileConfigIdentifierClasses();
    }
   
    protected static function remergeJustReplacedConfigFile(ConfigFileIdentifier $configFileIdentifier)
    {
        $fileProjectRelevantPath = $configFileIdentifier->getFileProjectRelevantPath();
        PixelConfigManager::remergeJustReplacedConfigFile($fileProjectRelevantPath);
    }

    protected function  initStubIdentifier(string $stubPath , string $newPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $newPath);
    }
 
    protected function composeConfigFileProjectConfigPath(ConfigFileIdentifier $configFileIdentifier) : string
    {
        $fileProjectRelevantPath = $configFileIdentifier->getFileProjectRelevantPath();
        return config_path($fileProjectRelevantPath);
    }
    
    protected function initConfigFileIdentifier(string $identifierClass) : ConfigFileIdentifier
    { 
        return $identifierClass::Singleton();
    }

    protected function replaceConfigFile(ConfigFileIdentifier $configFileIdentifier) : void
    {
        $filePackagePath = $configFileIdentifier->getFilePath();
        $fileProjectConfigPath = $this->composeConfigFileProjectConfigPath($configFileIdentifier);

        $stubIdentifier = $this->initStubIdentifier($filePackagePath , $fileProjectConfigPath); 
        $this->replaceStubFile($stubIdentifier);
    }

    protected function replaceTenancyConfigFile() : void
    {
        $identifierClass = $this->getTenancyConfigFileIdentifierClass() ;
        $configFileIdentifier = $this->initConfigFileIdentifier($identifierClass);

        $this->replaceConfigFile($configFileIdentifier);
        $this->remergeJustReplacedConfigFile($configFileIdentifier);
    }

    protected function replaceLaravelConfigFiles()
    {
        foreach($this->getReplacableFileConfigIdentifierClasses() as $identifierClass)
        {
            $configFileIdentifier = $this->initConfigFileIdentifier($identifierClass);

            $this->replaceConfigFile($configFileIdentifier);
            $this->remergeJustReplacedConfigFile($configFileIdentifier);
        }
    }

    protected function initPixelAppInstallingManager() : PixelAppInstallingManager
    {
        return PixelAppInstallingManager::Singleton();
    }

    public function installPackageConfigFiles() : void
    {
        $this->replaceLaravelConfigFiles();

        if($this->initPixelAppInstallingManager()->isInstallingForTenancySupporterApp())
        {
            $this->replaceTenancyConfigFile();
        }
    }
 
}