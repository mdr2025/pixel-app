<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->foreignId("client_id")->constrained("clients")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("country_id")->constrained("countries")->cascadeOnUpdate();
            $table->foreignId("city_id")->nullable()->constrained("cities")->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId("area_id")->nullable()->constrained("areas")->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('client_addresses');
    }
}
