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
        Schema::table("agent_num_x_agent", function(Blueprint $table){
            $table->dropForeign("agent_num_x_agent_fk_agent_number_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_number_index");

            $table->dropForeign("agent_num_x_agent_fk_agent_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_index");
        });

        Schema::table("agent_num_x_agent", function(Blueprint $table){
            $table->dropColumn("fk_agent_number");
            $table->dropColumn("fk_agent");

            $table->bigInteger("fk_agent_number_base")->unsigned()->nullable();
            $table->foreign('fk_agent_number_base')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number_base');
            
            $table->bigInteger("fk_agent_number_rel")->unsigned()->nullable();
            $table->foreign('fk_agent_number_rel')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number_rel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("agent_num_x_agent", function(Blueprint $table){
            $table->dropForeign("agent_num_x_agent_fk_agent_number_base_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_number_base_index");

            $table->dropForeign("agent_num_x_agent_fk_agent_number_rel_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_number_rel_index");
        });

        Schema::table("agent_num_x_agent", function(Blueprint $table){
            
            $table->dropColumn("fk_agent_number_base");
            $table->dropColumn("fk_agent_number_rel");

            $table->bigInteger("fk_agent_number")->unsigned()->nullable();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');
            
            $table->bigInteger("fk_agent")->unsigned()->nullable();
            $table->foreign('fk_agent')->references('id')->on('agents');
            $table->index('fk_agent');
        });
    }
};
