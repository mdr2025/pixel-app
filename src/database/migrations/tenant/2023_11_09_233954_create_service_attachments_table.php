<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained("services")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('service_attachments');
    }
}