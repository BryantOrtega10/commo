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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->text("description");
            $table->string("ip_address")->nullable();
            $table->string("module");
            $table->string("action");
            
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
        Schema::table("logs", function(Blueprint $table){
            $table->dropForeign("logs_fk_entry_user_foreign");
            $table->dropIndex("logs_fk_entry_user_index");

        });
        Schema::dropIfExists('logs');
    }
};
