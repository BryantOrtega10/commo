<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->date("date_birth")->nullable();
            $table->string("ssn")->nullable();

            $table->bigInteger("fk_gender")->unsigned()->nullable();
            $table->foreign('fk_gender')->references('id')->on('genders');
            $table->index('fk_gender');
            
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("phone_2")->nullable();
            $table->string("address")->nullable();
            $table->string("address_2")->nullable();

            $table->bigInteger("fk_state")->unsigned()->nullable();
            $table->foreign('fk_state')->references('id')->on('states');
            $table->index('fk_state');

            $table->string("city")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("national_producer")->nullable();
            $table->string("license_number")->nullable();

            $table->bigInteger("fk_sales_region")->unsigned()->nullable();
            $table->foreign('fk_sales_region')->references('id')->on('sales_regions');
            $table->index('fk_sales_region');

            $table->boolean("has_CMS")->nullable()->default(false);
            $table->date("CMS_date")->nullable();            
            $table->boolean("has_marketplace_cert")->nullable()->default(false);
            $table->date("marketplace_cert_date")->nullable();

            $table->date("contract_date")->nullable();
            $table->string("payroll_emp_ID")->nullable();

            $table->bigInteger("fk_contract_type")->unsigned()->nullable();
            $table->foreign('fk_contract_type')->references('id')->on('contract_types');
            $table->index('fk_contract_type');

            $table->string("company_EIN")->nullable();
            $table->text("agent_notes")->nullable();
            
            $table->bigInteger("fk_entry_user")->unsigned();
            $table->foreign('fk_entry_user')->references('id')->on('users');
            $table->index('fk_entry_user');

            $table->bigInteger("fk_user")->unsigned();
            $table->foreign('fk_user')->references('id')->on('users');
            $table->index('fk_user');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("agents", function(Blueprint $table){
            $table->dropForeign("agents_fk_gender_foreign");
            $table->dropIndex("agents_fk_gender_index");

            $table->dropForeign("agents_fk_state_foreign");
            $table->dropIndex("agents_fk_state_index");

            $table->dropForeign("agents_fk_sales_region_foreign");
            $table->dropIndex("agents_fk_sales_region_index");

            $table->dropForeign("agents_fk_contract_type_foreign");
            $table->dropIndex("agents_fk_contract_type_index");

            $table->dropForeign("agents_fk_entry_user_foreign");
            $table->dropIndex("agents_fk_entry_user_index");

            $table->dropForeign("agents_fk_user_foreign");
            $table->dropIndex("agents_fk_user_index");
        });

        Schema::dropIfExists('agents');
    }
};
