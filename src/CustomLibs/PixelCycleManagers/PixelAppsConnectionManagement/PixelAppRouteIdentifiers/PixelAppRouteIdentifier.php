<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers;

use PixelApp\CustomLibs\MultipartValueHandlers\MultipartArrayConverters\MultipartArrayConverter;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\Interfaces\DataSendingRouteIdentifier;

abstract class PixelAppRouteIdentifier
{
    protected string $uri ;
    protected ?array $uriParameters  = null;  
    protected array $data = [];
    protected bool $dataFormatIsReady = false;


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
        $this->handleDataMultipartFormat();

        return $this->data;
    }

    
    protected function setData(?array $data = null) : void
    {
        if($data)
        {
            $this->data = $data ;
        }
    }

    protected function handleDataMultipartFormat() : void
    { 
        if(
            !empty($this->data) // to skip the other condition checking if data is empty
            &&
            !$this->dataFormatIsReady
            &&
            $this instanceof DataSendingRouteIdentifier 
            &&
            $this->shouldBeSentAsMultipart()
          )
        {
            $this->convertDataToMultipartData();
        } 

        $this->markDataFormatAsReady();
    }

    protected function markDataFormatAsReady() : void
    {
        $this->dataFormatIsReady = true;
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

    protected function convertDataToMultipartData() : void
    {
        $data = $this->initMultipartArrayConverter()->convert($this->data);
        $this->setData($data);
    }
   
}