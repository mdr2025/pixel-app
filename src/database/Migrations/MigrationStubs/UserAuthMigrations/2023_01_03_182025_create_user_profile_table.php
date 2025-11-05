<?php

use PixelApp\Models\UsersModule\UserProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('picture')->nullable();
            $table->enum('marital_status' , UserProfile::MARTIAL_STATUSES)->nullable();
            $table->enum('military_status' , UserProfile::MILITARY_STATUSES)->nullable();
            $table->string('national_id_number')->nullable()->unique();
            $table->string('passport_number')->nullable()->unique();
            $table->foreignId("nationality_id")->constrained("countries")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId("user_id")->unique()->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
};