<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\WorkSector\ClientsModule\Client;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid("hashed_id");
            $table->string('name');
            $table->boolean('is_lead');
            $table->enum('client_type', Client::CLIENT_TYPES);
            $table->enum('source_type', Client::CLIENT_SOURCE_TYPES);
            $table->string('main_phone')->unique();
            $table->string('contact_email')->nullable()->unique();
            $table->string('notes')->nullable();
            $table->foreignId("advertising_tool_id")->nullable()->constrained("advertising_tools")->nullOnDelete();
            $table->foreignId("sales_agent_id")->nullable()->constrained("sales_agents")->nullOnDelete();
            $table->foreignId("country_id")->nullable()->constrained("countries")->nullOnDelete();
            $table->foreignId("city_id")->nullable()->constrained("cities")->nullOnDelete();
            $table->foreignId("area_id")->nullable()->constrained("areas")->nullOnDelete();

            ///// only clients
            $table->string('billing_address')->nullable()->comment('for client');
            $table->string('registration_no')->nullable()->unique()->comment('for client');
            $table->string('taxes_no')->nullable()->unique()->comment('for client');
            ///// end clients
            $table->string('picture')->nullable();
            $table->enum('status', Client::CLIENT_STATUSES)->default('active');
            $table->datetime('accepted_at')->nullable(); //important for statistics and conversation rate
            $table->softDeletes();
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
        Schema::dropIfExists('clients');
    }
}
