<?php

namespace PixelApp\Database;

use CRUDServices\DatabaseManagers\MySqlDatabaseManager;
use Illuminate\Support\Facades\DB;
use Throwable;

class PixelDatabaseManager
{
     
    public static function truncateDBTable(string $tableName , bool $stopingForeignKeyChecks = true) : void
    {
        MySqlDatabaseManager::truncateDBTable($tableName , $stopingForeignKeyChecks);
    }
}