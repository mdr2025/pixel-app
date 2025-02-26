<?php

use App\Models\WorkSector\ClientsModule\PurchaseRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->date('due_date');
            $table->string('purchase_name');
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('number')->nullable();
            $table->enum('pr_type', PurchaseRequest::PR_TYPE);
            $table->text('notes');
            $table->enum('status', PurchaseRequest::STATUS)->default('pending');
            $table->json('extends_date')->nullable();
            $table->text('request_requirements')->nullable();
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
        Schema::dropIfExists('purchase_requests');
    }
}
