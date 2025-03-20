<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppStubsManager\StubIdentifiers;

class StubIdentifier
{
    protected string $stubPath ;
    protected string $replacingPath ;
    protected $contentCallback = null;
    // protected ?string $replacedFileTempPath = null;

    public function __construct(string $stubPath ,string $replacingPath )
    {
        $this->stubPath = $stubPath;
        $this->replacingPath = $replacingPath;
    }

    public static function create(string $stubPath ,string $replacingPath ) : self
    {
        return new static($stubPath , $replacingPath);
    }

    public function getStubPath() : string
    {
        return $this->stubPath;
    }
    
    public function getReplacingPath() : string
    {
        return $this->replacingPath;
    }

    public function callOnFileContent(callable $callback) : self
    {
        if(is_callable($callback))
        {
            $this->contentCallback = $callback;
        }
        return $this;
    }

    public function getContentCallback() : ?callable
    {
        return $this->contentCallback;
    }

    // public function setReplacedFileTempPath(string $replacedFileTempPath) : void
    // {
    //     $this->replacedFileTempPath = $replacedFileTempPath;
    // }
    
    // public function getReplacedFileTempPath() : ?string
    // {
    //     return $this->replacedFileTempPath;
    // }

}
