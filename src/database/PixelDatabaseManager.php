<?php

namespace PixelApp\Database;

use CRUDServices\DatabaseManagers\MySqlDatabaseManager;
use PixelApp\Database\Migrations\PixelMigrationsManager;
use PixelApp\Database\Seeders\PixelSeedersManager;

class PixelDatabaseManager
{ 

    public static function installAppDatabaseFiles() : void
    {
        static::replaceMigrationFiles();
        static::replaceSeedersFiles();
    }

    public static function getPixelSeedersManager() : PixelSeedersManager
    {
        return new PixelSeedersManager();
    }

    public static function getPixelMigrationsManager() : PixelMigrationsManager
    {
        return new PixelMigrationsManager();
    }

    protected static function replaceMigrationFiles() : void
    {
        static::getPixelMigrationsManager()->installMigrations();
    }
 
    protected static function replaceSeedersFiles() : void
    {
        static::getPixelSeedersManager()->installSeeders();
    }

    public static function truncateDBTable(string $tableName , bool $stopingForeignKeyChecks = true) : void
    {
        MySqlDatabaseManager::truncateDBTable($tableName , $stopingForeignKeyChecks);
    }
}