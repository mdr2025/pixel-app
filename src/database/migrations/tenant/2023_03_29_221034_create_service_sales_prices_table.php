<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSalesPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_sales_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('sale_price')->nullable();
            $table->foreignId('unit_id')->constrained('measurement_units')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('max_discount' ,5 ,2 )->nullable();
            $table->float('min_sale_price')->nullable();
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
        Schema::dropIfExists('service_sales_prices');
    }
}
