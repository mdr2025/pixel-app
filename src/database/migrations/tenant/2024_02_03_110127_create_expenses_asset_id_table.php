<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesAssetIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_asset_id', function (Blueprint $table) {
            $table->id();
            $table->foreignId("expense_id")->constrained("expenses")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("asset_id")->constrained("assets")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('expenses_asset_id');
    }
}
