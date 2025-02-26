<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->unsignedInteger('invoices_count')->nullable(); // null means that the package is not limited
            $table->unsignedInteger('employees_count')->nullable();
            $table->unsignedInteger('products_count')->nullable();
            $table->unsignedInteger('clients_count')->nullable();
            $table->unsignedInteger('vendors_count')->nullable();
            $table->unsignedInteger('inventories_count')->nullable();
            $table->unsignedInteger('treasueries_count')->nullable();
            $table->unsignedInteger('assets_count')->nullable();
            $table->unsignedInteger('quotations_count')->nullable();
            $table->unsignedInteger('banks_accounts_count')->nullable();
            $table->unsignedInteger('purchase_order_count')->nullable();
            $table->unsignedInteger('attachments_size')->nullable();
            $table->unsignedInteger('free_subscrip_period');
            $table->unsignedInteger('grace_period');
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
        Schema::dropIfExists('packages');
    }
}
