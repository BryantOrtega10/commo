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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();

            $table->date("app_submit_date")->nullable();
            $table->date("request_effective_date")->nullable();
            $table->date("original_effective_date")->nullable();
            $table->string("application_id")->nullable();
            $table->string("eligibility_id")->nullable();
            $table->string("proposal_id")->nullable();
            $table->string("contract_id")->nullable();
            $table->integer("num_adults")->nullable();
            $table->integer("num_dependents")->nullable();
            $table->decimal("premium")->nullable();
            
            $table->date("cancel_date")->nullable();
            $table->date("benefit_effective_date")->nullable();
            $table->tinyInteger("reenrollment")->default(0)->comment("0 No, 1 Yes")->nullable();
                        
            $table->tinyInteger("entry_method")->default(0)->comment("0 Manual, 1 AutoEntry")->nullable();

            $table->date("validation_date")->nullable();
            $table->string("validation_filename")->nullable();
            $table->text("auto_entry_note")->nullable();
            $table->date("auto_entry_date")->nullable();
            $table->string("auto_entry_filename")->nullable();
            $table->string("auto_entry_comp_type")->nullable();
            
            
            $table->text("review_note")->nullable();
            $table->boolean("non_commissionable")->default(0)->nullable();
            

            $table->bigInteger("fk_policy_status")->unsigned();
            $table->foreign('fk_policy_status')->references('id')->on('policy_status');
            $table->index('fk_policy_status');

            $table->bigInteger("fk_customer")->unsigned();
            $table->foreign('fk_customer')->references('id')->on('customers');
            $table->index('fk_customer');

            $table->bigInteger("fk_agent_number")->unsigned();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');

            $table->bigInteger("fk_product")->unsigned();
            $table->foreign('fk_product')->references('id')->on('products');
            $table->index('fk_product');

            $table->bigInteger("fk_enrollment_method")->unsigned()->nullable();
            $table->foreign('fk_enrollment_method')->references('id')->on('enrollment_methods');
            $table->index('fk_enrollment_method');
            
            $table->bigInteger("fk_county")->unsigned()->nullable();
            $table->foreign('fk_county')->references('id')->on('counties');
            $table->index('fk_county');

            $table->bigInteger("fk_entry_user")->unsigned();
            $table->foreign('fk_entry_user')->references('id')->on('users');
            $table->index('fk_entry_user');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("policies", function(Blueprint $table){

            $table->dropForeign("policies_fk_policy_status_foreign");
            $table->dropIndex("policies_fk_policy_status_index");

            $table->dropForeign("policies_fk_customer_foreign");
            $table->dropIndex("policies_fk_customer_index");

            $table->dropForeign("policies_fk_agent_number_foreign");
            $table->dropIndex("policies_fk_agent_number_index");

            $table->dropForeign("policies_fk_product_foreign");
            $table->dropIndex("policies_fk_product_index");

            $table->dropForeign("policies_fk_enrollment_method_foreign");
            $table->dropIndex("policies_fk_enrollment_method_index");

            $table->dropForeign("policies_fk_county_foreign");
            $table->dropIndex("policies_fk_county_index");

            $table->dropForeign("policies_fk_entry_user_foreign");
            $table->dropIndex("policies_fk_entry_user_index");
        });
        
        Schema::dropIfExists('policies');
    }
};
