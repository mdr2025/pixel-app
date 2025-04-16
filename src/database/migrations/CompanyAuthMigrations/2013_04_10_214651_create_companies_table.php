<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//for remove later
class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('name');
            $table->string('company_domain')->nullable();
            $table->string('company_sector');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('picture')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->string('employees_no')->nullable();
            $table->string('branches_no')->nullable();
            $table->enum('package_status', ['Basic', 'Upgraded-no-Due', 'Upgraded-in-Due', 'Upgraded-over-Due'])->nullable();
            $table->boolean('is_active')->default(0);
            $table->foreignId('package_id')->nullable();
            $table->text('dates')->nullable();
            $table->string('admin_email')->unique();
            // $table->string('billing_address')->nullable();
            $table->string('mobile_number');
            $table->enum(
                'registration_status',
                ['pending', 'approved', 'rejected']
            )->default('pending');
            $table->string('verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
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
        Schema::dropIfExists('companies');
    }
}
