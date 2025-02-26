<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->foreignId("vendor_id")->constrained("vendors")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("country_id")->constrained("countries");
            $table->foreignId("city_id")->nullable()->constrained("cities")->nullOnDelete();
            $table->foreignId("area_id")->nullable()->constrained("areas")->nullOnDelete();
            $table->string('floor')->nullable();
            $table->string('residential_block')->nullable();
            $table->string('street')->nullable();
            $table->string('apartment_number')->nullable();
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
        Schema::dropIfExists('vendor_addresses');
    }
}
