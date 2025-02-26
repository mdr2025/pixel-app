<?php

use App\Models\WorkSector\FinanceModule\NonTaxPurchaseInvoice\NonTaxPurchaseInvoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNontaxPurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nontax_purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date');
            $table->json('payment_date')->nullable();
            $table->string('invoice_number')->unique();
            $table->string('purchase_invoice_name')->unique();
            $table->double("value")->default(0);
            $table->double("paid_value")->nullable()->default(0);
            $table->double("rest_value")->nullable()->default(0);
            $table->double("refund_value")->nullable()->default(0);
            $table->foreignId("vendor_id")->constrained("vendors")->cascadeOnUpdate();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("vendor_order_id")->constrained("vendor_orders")->cascadeOnUpdate();
            $table->enum('invoice_status',NonTaxPurchaseInvoice::INVOICE_STATUS)->default('received');
            $table->enum('payment_status',NonTaxPurchaseInvoice::PAYMENT_STATUS)->default('not_paid');
            $table->mediumText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nontax_purchase_invoices');
    }
}
