<?php

use App\Models\WorkSector\VendorsModule\VendorOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('title')->unique();
            $table->date('date');
            $table->date('delivery_date');

            $table->foreignId("vendor_id")->constrained("vendors")->cascadeOnUpdate();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("payments_terms_id")->constrained("payment_terms")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("offer_id")->constrained("vendor_offers")->cascadeOnUpdate();

            $table->double('po_total_value');
            $table->double('po_sales_taxes_value');
            $table->double('po_add_and_discount_taxes_value');
            $table->mediumText('notes')->nullable();
            $table->enum('status',VendorOrder::STATUS)->default('in_progress');
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
        Schema::dropIfExists('vendor_orders');
    }
}
