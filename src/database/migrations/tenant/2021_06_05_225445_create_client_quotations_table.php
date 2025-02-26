<?php

use App\Models\WorkSector\ClientsModule\ClientQuotation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotations', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->date('date');
            $table->date('due_date');
            $table->string('name')->nullable()->unique();
            $table->string('title')->nullable();
            $table->enum('quotation_type', ClientQuotation::QUOTATION_TYPES);
            $table->foreignId("client_id")->constrained("clients")->cascadeOnUpdate();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete()->cascadeOnUpdate();

            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("payment_term_id")->constrained("payment_terms")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate()->cascadeOnDelete();

            $table->double('total_price')->nullable();
            $table->mediumText('pr_requirements')->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('status', ClientQuotation::QUOTATION_STATUS)->default('Draft');

            $table->text('edit_requested')->nullable();
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
        Schema::dropIfExists('client_quotations');
    }
}
