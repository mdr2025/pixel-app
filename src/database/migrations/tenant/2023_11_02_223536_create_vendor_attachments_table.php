<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained("vendors")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('vendor_attachments');
    }
}
