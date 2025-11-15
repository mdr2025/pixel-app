<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PixelApp\Database\PixelDatabaseManager;

abstract class CompanyDataResettingBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    abstract protected function seedDatabase();
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // DB::beginTransaction();
        // try {
            //trucate tables
            $this->trucateTables($this->getTables());

            //seeding database
            $this->seedDatabase();

                // DB::commit(); 
        // } catch (\Throwable $e) 
        // {
            //rollback the opened transaction 
            // DB::rollBack(); 
            
        //     throw $e;
        // } finally {
        //     //ensure the foreing key check is enabled.
        //     DB::statement('SET FOREIGN_KEY_CHECKS=1');
        // }
    }

    private function truncateTable(string $table) : void
    {
        PixelDatabaseManager::truncateDBTable($table);
    }

    private function trucateTables(array $tables, int $chunkSize = 5, int $time = 0): void
    {
        //disable foreign key check
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        //trncate selected tables
        foreach($tables as $table)
        {
            $this->truncateTable($table);
        }
        // collect($tables)->each(fn($table) => DB::table($table)->truncate());
        //enable foreign key check
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function getTables(): array
    {
        $dbTables =  array_map(function($tableInfo)
                    {
                        return $tableInfo["name"];
                    } , Schema::getTables());
        
        $excludedTables = config('system-resetting-excluded-seeding-tables', []);
        //
        return array_diff($dbTables, $excludedTables);
    }
}
