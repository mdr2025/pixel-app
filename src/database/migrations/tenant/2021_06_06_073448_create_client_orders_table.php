<?php

use App\Models\WorkSector\ClientsModule\ClientOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->date('date');
            $table->date('delivery_date');
            $table->foreignId('quotation_id')->constrained('client_quotations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("client_id")->constrained("clients")->cascadeOnDelete();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("payment_term_id")->constrained("payment_terms")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->enum('type', ClientOrder::TYPE);
            $table->mediumText('notes')->nullable();
            $table->double('tax_values');
            $table->double('total_price');
            $table->double('total_after_taxes');
            $table->string('number')->nullable();
            $table->json('taxes')->nullable();
            $table->enum('operation_status', ClientOrder::STATUS)->default('in_progress');
            $table->enum('time_status', ClientOrder::TIME_STATUS)->default('normal');

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
        Schema::dropIfExists('client_orders');
    }
}
