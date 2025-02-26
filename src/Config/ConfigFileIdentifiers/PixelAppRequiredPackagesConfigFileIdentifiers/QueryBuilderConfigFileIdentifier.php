<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
 

class QueryBuilderConfigFileIdentifier extends PixelAppRequiredPackagesConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "query-builder";
    }

    public function getConfigPublishGroupingKeyNames() : array
    {
        return [  "pixel-query-builder-config" ];
    } 
}