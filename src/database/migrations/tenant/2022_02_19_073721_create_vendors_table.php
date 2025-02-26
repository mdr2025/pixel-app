<?php

use App\Models\WorkSector\VendorsModule\Vendors\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->string('name');
            $table->enum('vendor_type', Vendor::VENDOR_TYPES);
            $table->string('main_phone')->unique();
            $table->string('contact_email')->nullable()->unique();
            $table->string('notes')->nullable();
            $table->foreignId("country_id")->nullable()->constrained("countries")->nullOnDelete();
            $table->foreignId("city_id")->nullable()->constrained("cities")->nullOnDelete();
            $table->foreignId("area_id")->nullable()->constrained("areas")->nullOnDelete();

            $table->string('billing_address')->nullable()->comment('for client');
            $table->string('registration_no')->nullable()->unique()->comment('for client');
            $table->string('taxes_no')->nullable()->unique()->comment('for client');
            $table->string('picture')->nullable();
            $table->enum('status', Vendor::VENDOR_STATUSES)->default('active');
            $table->softDeletes();
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
        Schema::dropIfExists('vendors');
    }
}
