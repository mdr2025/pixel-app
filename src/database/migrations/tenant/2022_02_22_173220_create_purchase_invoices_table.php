<?php

use App\Models\WorkSector\FinanceModule\PurchaseInvoices\PurchaseInvoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date');
            $table->string('invoice_number')->unique();
            $table->string('purchase_invoice_name')->unique();
            $table->foreignId("vendor_id")->constrained("vendors")->cascadeOnUpdate();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("vendor_order_id")->constrained("vendor_orders")->cascadeOnUpdate();

            $table->double('invoice_without_taxes');
            $table->double('invoice_after_taxes');

            $table->enum('invoice_status',PurchaseInvoice::INVOICE_STATUS)->default('received');
            $table->enum('payment_status',PurchaseInvoice::PAYMENT_STATUS)->default('not_paid');
            $table->json('taxes');
            $table->mediumText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_invoices');
    }
}
