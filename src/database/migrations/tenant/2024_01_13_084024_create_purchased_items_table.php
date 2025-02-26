<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('hashed_id');
            $table->string('name');
            $table->mediumText('description');
            $table->string('model');
            $table->string('picture')->nullable();
            //foreign keys
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('purchased_items_category')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('purchased_items');
    }
}
