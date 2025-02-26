<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
  
class ExcelConfigFileIdentifier extends PixelAppRequiredPackagesConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "excel";
    }
    public function getConfigPublishGroupingKeyNames() : array
    {
        return [ "pixel-excel-config" ];
    } 
 
}