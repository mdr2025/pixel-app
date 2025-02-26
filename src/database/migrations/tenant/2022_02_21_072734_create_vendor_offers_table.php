<?php

use App\Models\WorkSector\VendorsModule\VendorOffer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained('vendor_purchase_requests' ,'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('vendor_id')->constrained('vendors' ,'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title');
            $table->string('name');
            $table->foreignId('department_id')->constrained('departments','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies' ,'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('payment_term_id')->constrained('payment_terms' ,'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('validity');
            $table->double('total');
            $table->date('delivery_date');
            $table->enum('status' , VendorOffer::STATUS)->default('pending');
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
        Schema::dropIfExists('vendor_offers');
    }
}
