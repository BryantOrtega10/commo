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
        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            
            $table->date("statement_date");

            $table->bigInteger("fk_agent_number")->unsigned();
            $table->foreign('fk_agent_number')->references('id')->on('agent_numbers');
            $table->index('fk_agent_number');

            $table->integer("number_policies")->default(0)->nullable();
            $table->decimal("total")->default(0)->nullable();
            
            $table->tinyInteger("status")->comment("0 - Just created, 1 - Approved")->nullable()->default(0);

            $table->bigInteger("fk_approve_user")->unsigned()->nullable();;
            $table->foreign('fk_approve_user')->references('id')->on('users');
            $table->index('fk_approve_user');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("statements", function(Blueprint $table){
            
            $table->dropForeign("statements_fk_agent_number_foreign");
            $table->dropIndex("statements_fk_agent_number_index");

            $table->dropForeign("statements_fk_approve_user_foreign");
            $table->dropIndex("statements_fk_approve_user_index");
        });

        Schema::dropIfExists('statements');
    }
};
