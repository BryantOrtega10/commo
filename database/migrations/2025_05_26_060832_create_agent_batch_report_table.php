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
        Schema::create('agent_batch_report', function (Blueprint $table) {
            $table->id();
            $table->date("statement_date");
            $table->tinyInteger("export_file_type")->comment("0 - Pdf, 1 - Excel");
            $table->integer("total");
            $table->integer("generated")->nullable()->default(0);

            $table->dateTime("processing_start_date")->nullable();
            $table->dateTime("processing_end_date")->nullable();

            $table->tinyInteger("status")->comment("0 - Generating, 1 - Generated")->default(0);

            $table->bigInteger("fk_entry_user")->unsigned();
            $table->foreign('fk_entry_user')->references('id')->on('users');
            $table->index('fk_entry_user');

            $table->timestamps();
        });

        Schema::create('agent_batch_report_item', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("fk_report")->unsigned();
            $table->foreign('fk_report')->references('id')->on('agent_batch_report')->onDelete("cascade");
            $table->index('fk_report');

            $table->bigInteger("fk_statement")->unsigned();
            $table->foreign('fk_statement')->references('id')->on('statements');
            $table->index('fk_statement');

            $table->tinyInteger("status")->comment("0 - not generated, 1 - generated")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("agent_batch_report", function(Blueprint $table){
            $table->dropForeign("agent_batch_report_fk_entry_user_foreign");
            $table->dropIndex("agent_batch_report_fk_entry_user_index");

        });

        Schema::table("agent_batch_report_item", function(Blueprint $table){
            $table->dropForeign("agent_batch_report_item_fk_report_foreign");
            $table->dropIndex("agent_batch_report_item_fk_report_index");

            $table->dropForeign("agent_batch_report_item_fk_statement_foreign");
            $table->dropIndex("agent_batch_report_item_fk_statement_index");
        });

        Schema::dropIfExists('agent_batch_report_item');
        Schema::dropIfExists('agent_batch_report');
    }
};
