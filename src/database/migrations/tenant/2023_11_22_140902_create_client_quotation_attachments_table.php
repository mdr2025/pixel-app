<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuotationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotation_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_quotation_id')->constrained("client_quotations")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('client_quotation_attachments');
    }
}
