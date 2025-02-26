<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsCategoriesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_categories_lists', function (Blueprint $table) {
            $table->foreignId('vendor_id')->constrained('vendors','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vendor_category_id')->constrained('vendors_categories','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['vendor_id','vendor_category_id']);
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
        Schema::dropIfExists('vendors_categories_lists');
    }
}
