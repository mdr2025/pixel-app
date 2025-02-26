<?php

use App\Models\WorkSector\ClientsModule\ClientQuotationItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ClientQuotationItem::TYPE)->nullable();
            $table->foreignId('client_quotation_id')->constraind('client_quotations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('category_id');
            $table->integer('quantity')->nullable();
            $table->double('discount')->nullable();
            $table->double('price')->nullable();
            $table->string('picture');
            $table->unsignedBigInteger('item_id');
            $table->double('total')->nullable();
            $table->double('min_sale_price')->nullable();
            $table->double('original_sale_price')->nullable();
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
        Schema::dropIfExists('client_quotation_items');
    }
}
