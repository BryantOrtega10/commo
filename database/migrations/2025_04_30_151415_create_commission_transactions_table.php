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
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();

            $table->date("check_date")->nullable();
            $table->date("statement_date");
            $table->date("submit_date");

            $table->date("original_effective_date")->nullable();
            $table->date("benefit_effective_date")->nullable();
            $table->date("cancel_date")->nullable();
            $table->date("initial_payment_date")->nullable();
            $table->date("accounting_date")->nullable();

            $table->decimal("comp_amount");
            $table->decimal("flat_rate")->nullable();

            $table->decimal("premium_percentaje")->nullable();
            $table->decimal("premium_amount")->nullable();
            
            $table->string("event_type")->nullable();
            $table->string("exchange_ind")->nullable();

            $table->boolean("is_adjustment")->nullable();
            
            $table->smallInteger("comp_year")->nullable();

            $table->boolean("is_qualified")->nullable();   

            $table->tinyInteger("rate_type")->comment("1 - Percentage, 2 - Flat Rate, 3 - Flat Rate per member")->nullable();

            $table->bigInteger("fk_agency_code")->unsigned()->nullable();
            $table->foreign('fk_agency_code')->references('id')->on('agency_codes');
            $table->index('fk_agency_code');

            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers');
            $table->index('fk_carrier');

            $table->bigInteger("fk_business_segment")->unsigned()->nullable();
            $table->foreign('fk_business_segment')->references('id')->on('business_segments');
            $table->index('fk_business_segment');

            $table->bigInteger("fk_business_type")->unsigned()->nullable();
            $table->foreign('fk_business_type')->references('id')->on('business_types');
            $table->index('fk_business_type');

            $table->bigInteger("fk_compensation_type")->unsigned()->nullable();
            $table->foreign('fk_compensation_type')->references('id')->on('compensation_types');
            $table->index('fk_compensation_type');

            $table->bigInteger("fk_amf_compensation_type")->unsigned()->nullable();
            $table->foreign('fk_amf_compensation_type')->references('id')->on('amf_compensation_types');
            $table->index('fk_amf_compensation_type');

            $table->bigInteger("fk_plan_type")->unsigned()->nullable();
            $table->foreign('fk_plan_type')->references('id')->on('plan_types');
            $table->index('fk_plan_type');

            $table->bigInteger("fk_product")->unsigned()->nullable();
            $table->foreign('fk_product')->references('id')->on('products')->onDelete("cascade");
            $table->index('fk_product');

            $table->bigInteger("fk_product_type")->unsigned()->nullable();
            $table->foreign('fk_product_type')->references('id')->on('product_types');
            $table->index('fk_product_type');

            $table->bigInteger("fk_tier")->unsigned()->nullable();
            $table->foreign('fk_tier')->references('id')->on('tiers');
            $table->index('fk_tier');

            $table->bigInteger("fk_county")->unsigned()->nullable();
            $table->foreign('fk_county')->references('id')->on('counties');
            $table->index('fk_county');

            $table->bigInteger("fk_policy")->unsigned()->nullable();
            $table->foreign('fk_policy')->references('id')->on('policies');
            $table->index('fk_policy');

            $table->bigInteger("fk_agent_number")->unsigned()->nullable();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');

            $table->bigInteger("fk_comm_upload_row")->unsigned();
            $table->foreign('fk_comm_upload_row')->references('id')->on('commission_upload_rows')->onDelete("cascade");
            $table->index('fk_comm_upload_row');           

            $table->string("adjusment_subscriber")->nullable();

            $table->text("notes")->nullable();

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
        Schema::table("commission_transactions", function(Blueprint $table){
            
            $table->dropForeign("commission_transactions_fk_agency_code_foreign");
            $table->dropIndex("commission_transactions_fk_agency_code_index");

            $table->dropForeign("commission_transactions_fk_carrier_foreign");
            $table->dropIndex("commission_transactions_fk_carrier_index");

            $table->dropForeign("commission_transactions_fk_business_segment_foreign");
            $table->dropIndex("commission_transactions_fk_business_segment_index");
            
            $table->dropForeign("commission_transactions_fk_business_type_foreign");
            $table->dropIndex("commission_transactions_fk_business_type_index");

            $table->dropForeign("commission_transactions_fk_compensation_type_foreign");
            $table->dropIndex("commission_transactions_fk_compensation_type_index");

            $table->dropForeign("commission_transactions_fk_amf_compensation_type_foreign");
            $table->dropIndex("commission_transactions_fk_amf_compensation_type_index");

            $table->dropForeign("commission_transactions_fk_plan_type_foreign");
            $table->dropIndex("commission_transactions_fk_plan_type_index");

            $table->dropForeign("commission_transactions_fk_product_foreign");
            $table->dropIndex("commission_transactions_fk_product_index");

            $table->dropForeign("commission_transactions_fk_product_type_foreign");
            $table->dropIndex("commission_transactions_fk_product_type_index");

            $table->dropForeign("commission_transactions_fk_tier_foreign");
            $table->dropIndex("commission_transactions_fk_tier_index");

            $table->dropForeign("commission_transactions_fk_county_foreign");
            $table->dropIndex("commission_transactions_fk_county_index");

            $table->dropForeign("commission_transactions_fk_policy_foreign");
            $table->dropIndex("commission_transactions_fk_policy_index");

            $table->dropForeign("commission_transactions_fk_entry_user_foreign");
            $table->dropIndex("commission_transactions_fk_entry_user_index");

            $table->dropForeign("commission_transactions_fk_agent_number_foreign");
            $table->dropIndex("commission_transactions_fk_agent_number_index");

            $table->dropForeign("commission_transactions_fk_comm_upload_row_foreign");
            $table->dropIndex("commission_transactions_fk_comm_upload_row_index");            
        });

        Schema::dropIfExists('commission_transactions');
    }
};
