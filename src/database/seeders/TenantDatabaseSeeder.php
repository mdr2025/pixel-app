<?php

namespace Database\Seeders;

use Database\Seeders\GeneralSeeders\AllLocationDataDatabaseSeeder;
use Database\Seeders\GeneralSeeders\CurrenciesSeeder;
use Database\Seeders\TenantSeeders\BranchesSeeder;
use Database\Seeders\TenantSeeders\DepartmentsTableSeeder;
use Database\Seeders\TenantSeeders\PermissionsSeeder;
use Database\Seeders\TenantSeeders\RolesSeeder;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
//            AssetsCategoryTableSeeder::class,
//            AssetExpenseTableSeeder::class,
//            AssetTableSeeder::class,
//            BonusTableSeeder::class,
//            ClientCategoryTableSeeder::class,
//            ClientOrderTableSeeder::class,
//            ClientPoExpenseTableSeeder::class,r
//            ClientQuotationTableSeeder::class,
//            ClientTableSeeder::class,
//            ClientVisitExpenseTableSeeder::class,
//            CompanyOperationExpense::class,
            AllLocationDataDatabaseSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            CurrenciesSeeder::class,
            DepartmentsTableSeeder::class,
            BranchesSeeder::class,
//            SignUpUserFactory::class,
//            EmployeeUserSeeder::class,
//            CustodyTableSeeder::class,
//            ExchangeExpenseTableSeeder::class,
//            ExpenseTypeTableSeeder::class,
//            InsuranceExpenseTableSeeder::class,
//            InsuranceTypeTableSeeder::class,
//            MarketingExpenseTableSeeder::class,
//            PaymentMethodsTableSeeder::class,
//            PaymentTermTableSeeder::class,
//            PurchaseExpenseTableSeeder::class,
//            PurchaseInvoiceTableSeeder::class,
//            TaxTypeTableSeeder::class,
//            TenderTableSeeder::class,
//            VendorTableSeeder::class,
//            PurchaseOrderTypeTableSeeder::class,
//            TaxExpenseTableSeeder::class,
        ]);
    }
}
