<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_address_id')->constrained('vendor_addresses')->cascadeOnDelete();
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
        Schema::dropIfExists('vendor_contacts');
    }
}
