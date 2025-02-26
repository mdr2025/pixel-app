<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->mediumText('description')->nullable();
            $table->string('picture')->nullable();
            $table->date('buying_date');
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("category_id")->constrained("assets_categories")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate()->cascadeOnDelete();
            $table->double("total");
            $table->mediumText('notes')->nullable();
            $table->enum('status', ['Available', 'In Maintenance'])->default('Available');
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
        Schema::dropIfExists('assets');
    }
}
