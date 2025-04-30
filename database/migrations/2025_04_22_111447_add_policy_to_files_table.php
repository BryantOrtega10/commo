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
        Schema::table("files", function(Blueprint $table){
            $table->bigInteger("fk_policy")->unsigned()->nullable();
            $table->foreign('fk_policy')->references('id')->on('policies');
            $table->index('fk_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("files", function(Blueprint $table){
            $table->dropForeign("files_fk_policy_foreign");
            $table->dropIndex("files_fk_policy_index");
            $table->dropColumn("fk_policy");
        });
    }
};
