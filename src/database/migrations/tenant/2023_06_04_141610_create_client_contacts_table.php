<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_address_id')->constrained('client_addresses')->cascadeOnDelete();
            $table->string('contact_name');
            $table->string('contact_phone')->unique();
            $table->string('job_role')->nullable()->comment("For Company's Types Clients");
            $table->string('contact_email')->nullable()->unique()->comment("For Company's Types Clients");
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
        Schema::dropIfExists('client_contacts');
    }
}
