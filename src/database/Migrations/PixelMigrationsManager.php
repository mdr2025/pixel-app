<?php

namespace PixelApp\Database\Migrations;


class PixelMigrationsManager
{

    protected static function initPixelMigrationsStubManager() : PixelMigrationsStubManager
    {
        return PixelMigrationsStubManager::Singleton();
    }

    public static function installMigrations() : void
    {
        static::initPixelMigrationsStubManager()->replaceMigrationStubs();
    }
}