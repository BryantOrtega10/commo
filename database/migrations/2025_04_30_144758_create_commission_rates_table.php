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
        Schema::create('commission_rates', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("fk_agent_number")->unsigned();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');
            
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

            $table->bigInteger("fk_region")->unsigned()->nullable();
            $table->foreign('fk_region')->references('id')->on('regions');
            $table->index('fk_region');

            $table->string("policy_contract_id")->nullable();

            $table->bigInteger("fk_tx_type")->unsigned()->nullable();
            $table->foreign('fk_tx_type')->references('id')->on('tx_types');
            $table->index('fk_tx_type');

            $table->tinyInteger("agent_type")->unsigned()->comment("0 - Writting Agent, 1 - Override Agent, 2 - Mentor Agent, 3 - Carrier Agent")->nullable();

            $table->date("submit_from")->nullable();
            $table->date("submit_to")->nullable();

            $table->date("statement_from")->nullable();
            $table->date("statement_to")->nullable();

            $table->date("original_effective_from")->nullable();
            $table->date("original_effective_to")->nullable();
            
            $table->date("benefit_effective_from")->nullable();
            $table->date("benefit_effective_to")->nullable();

            $table->decimal("flat_rate")->nullable();
            
            $table->tinyInteger("rate_type")->comment("1 - Percentage, 2 - Flat Rate, 3 - Flat Rate per member")->nullable();
            
            $table->decimal("rate_amount",10,8)->nullable();
            
            $table->integer("order")->nullable()->default(0);

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
        Schema::table("commission_rates", function(Blueprint $table){
            
            $table->dropForeign("commission_rates_fk_agent_number_foreign");
            $table->dropIndex("commission_rates_fk_agent_number_index");

            $table->dropForeign("commission_rates_fk_business_segment_foreign");
            $table->dropIndex("commission_rates_fk_business_segment_index");

            $table->dropForeign("commission_rates_fk_business_type_foreign");
            $table->dropIndex("commission_rates_fk_business_type_index");

            $table->dropForeign("commission_rates_fk_compensation_type_foreign");
            $table->dropIndex("commission_rates_fk_compensation_type_index");

            $table->dropForeign("commission_rates_fk_amf_compensation_type_foreign");
            $table->dropIndex("commission_rates_fk_amf_compensation_type_index");

            $table->dropForeign("commission_rates_fk_plan_type_foreign");
            $table->dropIndex("commission_rates_fk_plan_type_index");

            $table->dropForeign("commission_rates_fk_product_foreign");
            $table->dropIndex("commission_rates_fk_product_index");

            $table->dropForeign("commission_rates_fk_product_type_foreign");
            $table->dropIndex("commission_rates_fk_product_type_index");

            $table->dropForeign("commission_rates_fk_tier_foreign");
            $table->dropIndex("commission_rates_fk_tier_index");

            $table->dropForeign("commission_rates_fk_county_foreign");
            $table->dropIndex("commission_rates_fk_county_index");

            $table->dropForeign("commission_rates_fk_region_foreign");
            $table->dropIndex("commission_rates_fk_region_index");

            $table->dropForeign("commission_rates_fk_tx_type_foreign");
            $table->dropIndex("commission_rates_fk_tx_type_index");

            $table->dropForeign("commission_rates_fk_entry_user_foreign");
            $table->dropIndex("commission_rates_fk_entry_user_index");
                 
        });

        Schema::dropIfExists('commission_rates');
    }
};
