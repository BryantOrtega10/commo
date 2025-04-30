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
        Schema::table('policies', function (Blueprint $table) {
            $table->bigInteger("fk_agent_number_1")->unsigned()->nullable();
            $table->foreign('fk_agent_number_1')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number_1');

            $table->bigInteger("fk_agent_number_2")->unsigned()->nullable();
            $table->foreign('fk_agent_number_2')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number_2');

            $table->integer("count_commisions")->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            
            $table->dropForeign("policies_fk_agent_number_1_foreign");
            $table->dropIndex("policies_fk_agent_number_1_index");

            $table->dropForeign("policies_fk_agent_number_2_foreign");
            $table->dropIndex("policies_fk_agent_number_2_index");

            $table->dropColumn("fk_agent_number_1");
            $table->dropColumn("fk_agent_number_2");
            $table->dropColumn("count_commisions");
            
        });
    }
};
