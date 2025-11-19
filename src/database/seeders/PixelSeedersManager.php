<?php

namespace PixelApp\Database\Seeders;


class PixelSeedersManager
{

    protected static function initPixelSeedersStubManager() : PixelSeedersStubManager
    {
        return PixelSeedersStubManager::Singleton();
    }

    public static function installSeeders() : void
    {
        static::initPixelSeedersStubManager()->replaceSeederStubs();
    }

    

    public static function get() : string
    {

    }
}