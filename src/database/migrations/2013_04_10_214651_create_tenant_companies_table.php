<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_companies', function (Blueprint $table) {
            $table->id();
            $table->string("hashed_id");
            $table->string('company_id')->nullable();
            $table->string('name')->index();
            $table->string('domain')->nullable()->unique();
            $table->string('sector');
            $table->string('logo')->nullable() ;
            $table->foreignId("country_id")->constrained("countries")->cascadeOnUpdate();
            $table->boolean('active')->default(1)->comment("By default it is active and pending ");
            $table->enum('registration_status', TenantCompany::REGISTRATIONS_STATUSES )->default(TenantCompany::REGISTRATIONS_DEFAULT_STATUS);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
