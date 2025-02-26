<?php

use App\Models\WorkSector\FinanceModule\Investor\Investor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->string('picture')->nullable();
            $table->string("name")->unique();
            $table->foreignId('country_id')->constrained('countries','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies' ,'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('payment_terms');
            $table->double('amount');
            $table->double('percentage');
            $table->string('phone')->unique();
            $table->string('another_phone')->nullable();
            $table->string('email')->unique();
            $table->mediumText('notes')->nullable();
            $table->mediumText('exit_strategy')->nullable();
            $table->enum('type' , Investor::TYPE);
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
        Schema::dropIfExists('investors');
    }
}
