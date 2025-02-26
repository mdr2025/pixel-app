<?php

use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('hashed_id')->unique();
            $table->string('email')->unique();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("name");
            $table->string("full_name")->nullable()->comment("The name composed from 4 names for some countries Citizens");
            $table->string('password');
            $table->string('mobile', 20)->unique();
            $table->enum('user_type', User::UserAllowedTypes  )->default(User::UserDefaultType );
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_token')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->foreignId("department_id")->nullable()->constrained("departments")->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('employee_id')->nullable()->comment("EMP-auto_increment_id");
            $table->enum('status', User::UserStatusNames)->default(User::UserDefaultInitStatusValue);
            $table->boolean('default_user')->default(0);
            $table->foreignId("role_id")->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("previous_role_id")->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();
            // $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->cascadeOnUpdate();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
