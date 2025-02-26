<?php

use App\Models\WorkSector\FinanceModule\CompanyTransactions\CompanyOutFlowPart1;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutflowSectionOneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outflow_section_one', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trans_id')->nullable()->constrained('company_trans_outflows')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tender_date')->nullable();
            $table->enum('insurance_type', ['social_insurance', 'medical_insurance', 'tender_insurance', 'asset_insurance', 'other_insurance'])->nullable();
            $table->enum('tender_type', ['InitialInsurance', 'FinalInsurance'])->nullable();
            $table->foreignId('official_reciept_issuer_id')->nullable()->constrained('offical_reciept_issuers')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('client_order_id')->nullable()->constrained('client_orders')->cascadeOnDelete()->cascadeOnDelete();
            $table->unsignedBigInteger('marketable_id')->nullable();
            $table->string('marketable_type')->nullable();
            $table->foreignId('reciept_number_id')->nullable()->constrained('official_reciepts')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained('company_inventories')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->cascadeOnDelete()->cascadeOnDelete();
            $table->enum('client_type',CompanyOutFlowPart1::CLIENTTYPE)->nullable();
            $table->foreignId('expense_type_id')->nullable()->constrained('expense_types')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete()->cascadeOnDelete();
            $table->enum('expense_invoice', ['without_tax_invoice', 'with_tax_invoice', 'with_offical_reciept']);
            $table->string('insurance_duration')->nullable();
            $table->string('insurance_reference_number')->nullable();
            $table->string('months_list')->nullable();
            $table->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnDelete()->cascadeOnDelete();
            $table->string('tender_percentage')->nullable();
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
        Schema::dropIfExists('outflow_section_one');
    }
}
