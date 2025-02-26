<?php

use App\Models\PersonalSector\PersonalTransactions\Outflow\Expense;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->mediumText('notes')->nullable();
            $table->double('amount');
            $table->string('paid_to')->nullable();

            $table->enum('category',[Expense::CATEGORY]);

            $table->unsignedBigInteger('marketable_id')->nullable();
            $table->string('marketable_type')->nullable();


            $table->string('reciept_number')->nullable();
            $table->foreignId("inventory_id")->nullable()->constrained("company_inventories")->cascadeOnUpdate();

//            $table->foreignId("asset_id")->nullable()->constrained("assets")->cascadeOnUpdate();
            $table->json("asset")->nullable();
            $table->enum('client_type',Expense::CLIENTTYPE)->nullable();
            $table->foreignId("client_id")->nullable()->constrained("clients")->cascadeOnUpdate();
            $table->foreignId("client_po_id")->nullable()->constrained("client_orders")->cascadeOnUpdate();
            $table->foreignId("purchase_invoice_id")->nullable()->constrained("purchase_invoices")->cascadeOnUpdate();

            $table->foreignId("expense_type_id")->nullable()->constrained("expense_types")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("payment_method_id")->constrained("payment_methods")->cascadeOnUpdate();


            $table->enum('receiver_type',Expense::RECEIVERTYPE)->nullable();
            $table->foreignId("user_id")->nullable()->constrained("users")->cascadeOnUpdate();
            $table->foreignId("treasury_id")->nullable()->constrained("company_treasuries")->cascadeOnUpdate();
            $table->foreignId("bank_id")->nullable()->constrained("company_bank_accounts")->cascadeOnUpdate();

            // $table->unsignedBigInteger('tender_id');
            // $table->foreign('tender_id')->references('id')->on('tenders')->onUpdate('cascade');

            $table->enum('status',Expense::STATUS)->default('pending');
            $table->enum('expense_invoice',['without_pr','with_pr','with_or'])->nullable();


            $table->timestamp('accepted_at')->nullable();
            $table->foreignId("accepted_by")->nullable()->constrained("users")->cascadeOnUpdate();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId("rejected_by")->nullable()->constrained("users")->cascadeOnUpdate();
            $table->timestamps();
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
        Schema::dropIfExists('expenses');
    }
}
