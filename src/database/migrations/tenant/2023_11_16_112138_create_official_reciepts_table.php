<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialRecieptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_reciepts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('official_reciept_issuer_id')->constrained('offical_reciept_issuers')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('reciept_type_id')->constrained('offical_receipt_types')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->double('total');
            $table->enum('status',['draft' , 'pending','approved','rejected'])->default('pending');

            $table->text('notes')->nullable();
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
        Schema::dropIfExists('official_reciepts');
    }
}
