<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained("purchase_invoices")->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('path');
            $table->string('path_original');
            $table->string('type');
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
        Schema::dropIfExists('purchase_invoices_attachments');
    }
}
