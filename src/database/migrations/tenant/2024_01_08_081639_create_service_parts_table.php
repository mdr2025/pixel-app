<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_parts', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->string('name');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('tools_certificates');
            $table->mediumText('tools_description');
            $table->string('description');
            $table->string('picture')->nullable();
            $table->mediumText('notes')->nullable();

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
        Schema::dropIfExists('service_parts');
    }
}
