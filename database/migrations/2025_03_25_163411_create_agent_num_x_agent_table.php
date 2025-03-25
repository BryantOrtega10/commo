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
        Schema::create('agent_num_x_agent', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("type")->comment("0 - Agent Base, 1 - Mentor Agent, 2 - Override")->nullable()->default(0);
            
            $table->bigInteger("fk_agent_number")->unsigned()->nullable();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers')->onDelete('cascade');
            $table->index('fk_agent_number');
            
            $table->bigInteger("fk_agent")->unsigned()->nullable();
            $table->foreign('fk_agent')->references('id')->on('agents')->onDelete('cascade');
            $table->index('fk_agent');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("agent_num_x_agent", function(Blueprint $table){
            
            $table->dropForeign("agent_num_x_agent_fk_agent_number_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_number_index");

            $table->dropForeign("agent_num_x_agent_fk_agent_foreign");
            $table->dropIndex("agent_num_x_agent_fk_agent_index");

        });

        Schema::dropIfExists('agent_num_x_agent');
    }
};
