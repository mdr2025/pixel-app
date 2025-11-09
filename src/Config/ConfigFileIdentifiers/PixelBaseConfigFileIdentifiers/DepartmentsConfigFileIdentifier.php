<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class DepartmentsConfigFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
     

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "departments";
    } 
   
}