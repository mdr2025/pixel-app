<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers;

use PixelApp\CustomLibs\MultipartValueHandlers\MultipartArrayConverters\MultipartArrayConverter;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\Interfaces\DataSendingRouteIdentifier;

abstract class PixelAppRouteIdentifier
{
    protected string $uri ;
    protected ?array $uriParameters  = null;  
    protected array $data = [];


    public function __construct(
                                 string $uri ,
                                 ?array $data = null ,
                                 ?array $uriParameters = null
                                )
    {
        $this->setUri($uri);
        $this->setData($data);
        $this->setUriParameters($uriParameters);
    }

    protected function setUri(string $uri) : void
    {
        $this->uri = $uri;
    }

    protected function getUncompiledUri() : string
    {
        return $this->uri;
    }

    protected function getUriParameters() : ?array
    {
        return $this->uriParameters;
    }
    

    protected function setUriParameters(?array $uriParameters = null)
    {
        if($uriParameters)
        {
            $this->uriParameters = $uriParameters; 
        }
    }
    
    public function getData() : array
    {
        return $this->data;
    }

    protected function setData(?array $data = null) : void
    {
        if(!$data)
        {
            return;
        }

        if(
            $this instanceof DataSendingRouteIdentifier 
            &&
            $this->shouldBeSentAsMultipart()
          )
        {
            $data = $this->convertDataToMultipartData($data);
        }
        
        $this->data = $data ;
    }

    protected function replaceUriParameters($uri) : string
    {
        if( $parameters = $this->getUriParameters())
        {
            $keys =  array_keys($parameters);
            $replacedKeys = array_map(fn($k) => '{'.$k.'}', $keys);
            $values =  array_values($parameters);
            $uri = str_replace($replacedKeys , $values, $uri);
        }

        return $uri;
    }

    public function getUri() : string
    {
        $uri = $this->getUncompiledUri();
        return $this->replaceUriParameters($uri);
    }

    protected function initMultipartArrayConverter() : MultipartArrayConverter
    {
        return new MultipartArrayConverter();
    }

    protected function convertDataToMultipartData(array $data) : array
    {
        return $this->initMultipartArrayConverter()->convert($data);
    }
   
}