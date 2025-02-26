<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_hrs', function (Blueprint $table) {
            $table->id();
            $table->enum('employment_commitment', ['full_time', 'part_time', 'hourly_rate', 'per_job']);
            $table->enum('employment_type', ['direct_hired', 'trainee', 'on_contract', 'freelancer']);
            $table->date('date_of_hiring');
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
        Schema::dropIfExists('user_hrs');
    }
}
