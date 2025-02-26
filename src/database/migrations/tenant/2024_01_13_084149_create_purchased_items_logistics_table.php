<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedItemsLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_items_logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchased_id')->constrained('purchased_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('hs_code');
            $table->json('dimensions');
            $table->json('expiry_time');
            $table->json('weight');
            $table->json('unit_per_package');
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
        Schema::dropIfExists('purchased_items_logistics');
    }
}
