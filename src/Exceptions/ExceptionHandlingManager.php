<?php

namespace PixelApp\Exceptions;

class ExceptionHandlingManager
{

    protected static function initExceptionHandlingStubsManager() : ExceptionHandlingStubsManager
    {
        return ExceptionHandlingStubsManager::Singleton();
    }

    public static function installExceptionStubs() : void
    {
        static::initExceptionHandlingStubsManager()->installExceptionStubs();
    }

}