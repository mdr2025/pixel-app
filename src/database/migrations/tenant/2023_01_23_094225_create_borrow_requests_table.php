<?php

use App\Models\PersonalSector\BorrowRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();
            $table->string('approved_number')->nullable();
            $table->double('amount');
            $table->date('date');
            $table->foreignId('currency_id')->constrained('currencies' ,'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users' ,'id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->mediumText('reason');
            $table->string('period');
            $table->enum('status' , BorrowRequest::STATUS);
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
        Schema::dropIfExists('borrow_requests');
    }
}
