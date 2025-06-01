<?php

namespace PixelApp\Console;


class PixelConsoleManager
{ 
    protected static function initPixelConsoleStubManager() : PixelConsoleStubManager
    {
        return PixelConsoleStubManager::Singleton();
    }

    public static function installConsoleObjects() : void
    {
        static::initPixelConsoleStubManager()->installConsoleObjects();
    }
}