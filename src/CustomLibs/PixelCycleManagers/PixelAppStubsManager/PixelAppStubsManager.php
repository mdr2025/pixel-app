<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager;

use CustomFileSystem\CustomFileHandler;
use Illuminate\Support\Facades\File;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers\StubIdentifier;

class PixelAppStubsManager
{
    protected static array $instances = [];
    // protected array $replacedStubIdentifiers = [];

    private function __construct(){}

    public static function Singleton() : self
    {
        if(! array_key_exists( static::class , static::$instances ))
        {
            static::$instances[ static::class ] = new static();
        }
        
        return static::$instances[ static::class ];
    }

    // protected function getReplacedTempFolderRelevantPath() : string
    // {
    //     return "temp/replacedStubs";
    // }
    // protected function getReplacedTempFolderPath() : string
    // {
    //     return CustomFileHandler::getFileStoragePath($this->getReplacedTempFolderRelevantPath());
    // }

    // protected function ensureReplacedTempFolderPath() : void
    // {
        // if(!CustomFileHandler::IsFileExists($this->getReplacedTempFolderRelevantPath()))
        // {
        //     CustomFileHandler::
        // }
    // }

    // protected function initStubIdentifier(string $stubPath , string $newPath)  : StubIdentifier
    // {
    //     return StubIdentifier::create($stubPath , $newPath);
    // }

    // protected function addReplacedStubIdentifier(StubIdentifier $stubIdentifier) : void
    // {
    //     $this->replacedStubIdentifiers[] = $stubIdentifier;
    // }

    protected function callContentCallback(StubIdentifier $stubIdentifier , string $fileContent) : string
    {
        if($callback = $stubIdentifier->getContentCallback())
        {
            $fileContent = call_user_func($callback , $fileContent);
        }

        return $fileContent;
    }

    protected function getStubContent(StubIdentifier $stubIdentifier) : string
    {
        return File::get($stubIdentifier->getStubPath());
    }

    public function replaceStubFile(StubIdentifier $stubIdentifier) : void
    {
        $fileContent = $this->getStubContent($stubIdentifier);

        $fileContent = $this->callContentCallback($stubIdentifier , $fileContent);

        File::put($stubIdentifier->getReplacingPath() , $fileContent);

        // /**
        //  * @todo later
        //  * need to handle temorary old file for commiting and rolling back .... 
        //  */
    }

    // public function replaceStubFile(StubIdentifier $stubIdentifier) : void
    // {
    //     $stubIdentifier = $this->initStubIdentifier($stubPath , $newPath);
    //     $this->replaceFile($stubIdentifier);
    //     $this->addReplacedStubIdentifier($stubIdentifier);
    // }

    // public function commitReplacing() : void
    // {

    // }
    // public static function rollbackReplacing() : void
    // {

    // }
}