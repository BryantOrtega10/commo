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
        Schema::table('files', function (Blueprint $table) {
            $table->bigInteger("fk_agent_number")->unsigned()->nullable();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("files", function(Blueprint $table){
            $table->dropForeign("files_fk_agent_number_foreign");
            $table->dropIndex("files_fk_agent_number_index");
            $table->dropColumn("fk_agent_number");
        });
    }
};
