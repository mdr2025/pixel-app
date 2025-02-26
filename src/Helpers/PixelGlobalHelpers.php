<?php

namespace PixelApp\Helpers;

class PixelGlobalHelpers
{ 
    public static function pixelAppPackagePath(?string $subPath = null) : string
    {
        $path = base_path("vendor/muhammedaldrubi/PixelApp/src");
        if($subPath)
        {
            $path .= "/" . $subPath;
        }
        return $path;
    }
 
    public static function requirePhpFiles($directoryPath)
    {
        foreach (glob($directoryPath . '/*.php') as $fileName) {
            require $fileName; 
        }
    }
  
    public static function notFound()
    {
        die("not found");
    }

}
