<?php

namespace Database\Seeders\TenantSeeders;
use App\Models\WorkSector\FinanceModule\PurchaseInvoices\PurchaseInvoice;
use Illuminate\Database\Seeder;

class PurchaseInvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PurchaseInvoice::factory()->count(200)->create();
    }
}
