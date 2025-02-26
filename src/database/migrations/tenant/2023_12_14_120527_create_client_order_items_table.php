<?php

use App\Models\WorkSector\ClientsModule\ClientOrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_order_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ClientOrderItem::TYPE)->nullable();
            $table->foreignId('client_order_id')->constraind('client_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('category_id');
            $table->integer('quantity')->nullable();
            $table->double('discount')->nullable();
            $table->double('price')->nullable();
            $table->string('picture');
            $table->integer('item_id');
            $table->double('total')->nullable();
            $table->foreignId('unit_id')->nullable()->constraind('measurement_units')->cascadeOnDelete()->cascadeOnUpdate();
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('client_order_items');
    }
}
