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
        Schema::create('agent_numbers', function (Blueprint $table) {
            $table->id();

            $table->integer("agent_number");
            
            $table->bigInteger("fk_agency_code")->unsigned()->nullable();
            $table->foreign('fk_agency_code')->references('id')->on('agency_codes')->onDelete('cascade');
            $table->index('fk_agency_code');

            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers')->onDelete('cascade');
            $table->index('fk_carrier');

            $table->bigInteger("fk_agent_title")->unsigned()->nullable();
            $table->foreign('fk_agent_title')->references('id')->on('agent_titles')->onDelete('cascade');
            $table->index('fk_agent_title');

            $table->bigInteger("fk_agent_status")->unsigned()->nullable();
            $table->foreign('fk_agent_status')->references('id')->on('agent_status')->onDelete('cascade');
            $table->index('fk_agent_status');

            $table->bigInteger("fk_agency")->unsigned()->nullable()->comment("Pay to agency");
            $table->foreign('fk_agency')->references('id')->on('agencies')->onDelete('cascade');
            $table->index('fk_agency');

            $table->double("contract_rate");

            $table->bigInteger("fk_admin_fee")->unsigned()->nullable();
            $table->foreign('fk_admin_fee')->references('id')->on('admin_fees')->onDelete('cascade');
            $table->index('fk_admin_fee');
            
            $table->text("notes")->nullable();

            $table->bigInteger("fk_entry_user")->unsigned();
            $table->foreign('fk_entry_user')->references('id')->on('users')->onDelete('cascade');
            $table->index('fk_entry_user');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("agent_numbers", function(Blueprint $table){
            
            $table->dropForeign("agent_numbers_fk_agency_code_foreign");
            $table->dropIndex("agent_numbers_fk_agency_code_index");

            $table->dropForeign("agent_numbers_fk_carrier_foreign");
            $table->dropIndex("agent_numbers_fk_carrier_index");

            $table->dropForeign("agent_numbers_fk_agent_title_foreign");
            $table->dropIndex("agent_numbers_fk_agent_title_index");

            $table->dropForeign("agent_numbers_fk_agent_status_foreign");
            $table->dropIndex("agent_numbers_fk_agent_status_index");

            $table->dropForeign("agent_numbers_fk_agency_foreign");
            $table->dropIndex("agent_numbers_fk_agency_index");

            $table->dropForeign("agent_numbers_fk_admin_fee_foreign");
            $table->dropIndex("agent_numbers_fk_admin_fee_index");

            $table->dropForeign("agent_numbers_fk_entry_user_foreign");
            $table->dropIndex("agent_numbers_fk_entry_user_index");

        });
        
        Schema::dropIfExists('agent_numbers');
    }
};
