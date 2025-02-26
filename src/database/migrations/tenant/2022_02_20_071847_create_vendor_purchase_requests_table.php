<?php

use App\Models\WorkSector\VendorsModule\VendorPurchaseRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->date('due_date');
            $table->string('purchase_name');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('notes');
            $table->enum('pr_type', VendorPurchaseRequest::PR_TYPE);
            $table->text('request_requirements')->nullable();
            $table->enum('status', VendorPurchaseRequest::STATUS)->default('pending');
            $table->json('extends_date')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnUpdate()->cascadeOnDelete();

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
        Schema::dropIfExists('vendor_purchase_requests');
    }
}
