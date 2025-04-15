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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->text("description");
            
            $table->bigInteger("fk_activity")->unsigned()->nullable();
            $table->foreign('fk_activity')->references('id')->on('activities');
            $table->index('fk_activity');

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
        Schema::table("activity_logs", function(Blueprint $table){
            $table->dropForeign("activity_logs_fk_activity_foreign");
            $table->dropIndex("activity_logs_fk_activity_index");

            $table->dropForeign("activity_logs_fk_entry_user_foreign");
            $table->dropIndex("activity_logs_fk_entry_user_index");
        });

        Schema::dropIfExists('activity_logs');
    }
};
