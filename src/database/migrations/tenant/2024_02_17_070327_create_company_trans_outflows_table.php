<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTransOutflowsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_trans_outflows', function (Blueprint $table) {
            $table->id();
            ////////////////string fields/////////////
            $table->string('transaction_purpose');
            $table->string('profit_cycle');
            $table->string('paid_to')->nullable();
            $table->string('trans_reference')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('notes', 2000)->nullable();
            /////////////////numeric fields ///////////////////////
            $table->decimal('shareholder_percentage')->nullable();
            $table->unsignedInteger('amount');
            //////////////foreign key fields/////////////////////
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnDelete()->comment('added by user');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('shareholder_id')->nullable()->constrained('investors')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('card_number_id')->nullable()->constrained('visa_mastercard')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('borrow_id')->nullable()->constrained('borrow_requests')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained('company_bank_accounts')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('treasury_id')->nullable()->constrained('company_treasuries')->cascadeOnDelete()->cascadeOnDelete();
            ///////////////enum fields///////
            $table->enum('withdrawal_method', ['cash_at_bank', 'bank_cheque', 'via_atm', 'bank_transfer', 'via_card_online/offline']);
            $table->enum('transaction_type', ['from_bank_account', 'from_treasury']);
            //////////////date fields////////////////////
            $table->date('date');
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
        Schema::dropIfExists('company_trans_outflows');
    }
}
