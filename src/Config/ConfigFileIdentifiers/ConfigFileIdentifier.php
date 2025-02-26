<?php

namespace PixelApp\Config\ConfigFileIdentifiers;

abstract class ConfigFileIdentifier
{
    protected static array $instances = [];

    private function __construct(){}

    public static function Singleton() : ConfigFileIdentifier
    {
        if(! isset( static::$instances[ static::class  ] ))
        {
            static::$instances[ static::class  ] = new static();
        }

        return static::$instances[ static::class  ];
    }

    /**
     *  file name without extension
     */
    abstract public function getFileName() : string;

    public function getConfigKeyName() : string
    {
        return $this->getFileName();
    }
    
    public function getFileProjectRelevantPath() : string
    {
        return $this->getFileName(). $this->getFileExtension();
    }
  
    protected function getFilePackageConfigRelevantPath() : string
    {
        return $this->getFileProjectRelevantPath()  ;
    }
 
    public function getFilePath() : string
    {
        return static::getPackageConfigBasePath() 
               . "/" .
               $this->getFilePackageConfigRelevantPath() ;
    }   

    public function getFileExtension() : string
    {
        return ".php";
    }
    public static function getPackageConfigBasePath() : string
    {
        return __DIR__ . "/ConfigFiles";
    }
  
}