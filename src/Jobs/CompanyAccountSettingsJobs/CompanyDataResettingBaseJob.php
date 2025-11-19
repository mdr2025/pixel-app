<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PixelApp\Database\PixelDatabaseManager;
use PixelApp\Models\PixelModelManager;

abstract class CompanyDataResettingBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    abstract protected function seedDatabase();
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected string $resetType)
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
        try {
         
            $this->trucateTables();
         
            $this->seedDatabase();

            $this->resetUsers();

            Log::info('ResetCompanyDataJob: all operations completed successfully.');

                // DB::commit(); 
        }catch (\Throwable $e)
        {
            
            Log::error('ResetCompanyDataJob failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;

        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1'); ///////////////
        }
    }

    // reset users
    private function resetUsers(): void
    {
        if ($this->resetType == 'full')
        {
            Log::info('ResetCompanyDataJob: resetting users...');
            
            PixelModelManager::getUserModelClass()::where('role_id', '!=', 1)->orWhereNull('role_id')->delete(); // delete all users except super admin

            Log::info('ResetCompanyDataJob: users reset completed.');
        }
    }

    private function truncateTable(string $table) : void
    {
        PixelDatabaseManager::truncateDBTable($table);
    }

    private function trucateTables( int $chunkSize = 5, int $time = 0): void
    {
        Log::info('ResetCompanyDataJob: starting truncateTables...');

        //disable foreign key check
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        //trncate selected tables
        foreach($this->getTableNamesToTruncate() as $table)
        {
            $this->truncateTable($table);
        }

        Log::info('ResetCompanyDataJob: truncateTables completed.');

        // collect($tables)->each(fn($table) => DB::table($table)->truncate());
        //enable foreign key check
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
// private function truncateTables(array $tables, int $chunkSize = 5, int $time = 0): void
//     {
//         //disable foreign key check
//         DB::statement('SET FOREIGN_KEY_CHECKS=0');

//         //truncate selected tables with error handling
//         collect($tables)->each(fn($table) => DB::table($table)->truncate());

//         //enable foreign key check
//         DB::statement('SET FOREIGN_KEY_CHECKS=1');
//     }

    protected function getExcludedTablesBaseOnResetType() : array
    {
        return config('system-resetting-excluded-tables', [])[$this->resetType];
    }

    protected function fetchDataBaseTableNames() : array
    {
        return array_map(function($tableInfo)
                {
                    return $tableInfo["name"];
                } , Schema::getTables());
    }
    private function getTableNamesToTruncate(): array
    {
        $dbTables =  $this->fetchDataBaseTableNames();
        $excludedTables = $this->getExcludedTablesBaseOnResetType();

        return array_diff($dbTables, $excludedTables);
    }
}
