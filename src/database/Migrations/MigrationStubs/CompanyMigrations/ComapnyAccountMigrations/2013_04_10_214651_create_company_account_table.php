<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_account', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('cr_no')->nullable()->unique();
            $table->string('sector');
            $table->string("hashed_id");
            $table->string('logo')->nullable();
            $table->foreignId("country_id")->constrained("countries")->cascadeOnUpdate();
            $table->string('employees_no')->nullable();
            $table->string('branches_no')->nullable(); 
            $table->string('address')->nullable(); 
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate(); 
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
};