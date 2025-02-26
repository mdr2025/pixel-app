<?php

namespace PixelApp\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ResetCompanyDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        DB::beginTransaction();
        try {
            //trucate tables
            $this->trucateTables($this->getTables());
            //seed the current tenant
            Artisan::call('tenants:seed', [
                '--tenants' => [tenant()->getTenantKey()],
                '--class'   => 'TenantDatabaseForCompanyResetSeeder'
            ]); 
                DB::commit(); 
        } catch (\Throwable $e) 
        {
            //rollback the opened transaction 
            DB::rollBack(); 
            
            throw $e;
        } finally {
            //ensure the foreing key check is enabled.
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function trucateTables(array $tables, int $chunkSize = 5, int $time = 0): void
    {
        //disable foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        //trncate selected tables
        collect($tables)->each(fn($table) => DB::table($table)->truncate());
        //enable foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function getTables(): array
    {
        $dbTables = DB::getDoctrineSchemaManager()->listTableNames() ?? [];
        $excludedTables = config('excluded-tenants-seeding-tables', []);
        //
        return array_diff($dbTables, $excludedTables);
    }
}
