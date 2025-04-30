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
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->date("date_birth")->nullable();

            $table->bigInteger("fk_relationship")->unsigned()->nullable();
            $table->foreign('fk_relationship')->references('id')->on('relationships');
            $table->index('fk_relationship');
            
            $table->date("date_added")->nullable();

            $table->bigInteger("fk_policy")->unsigned()->nullable();
            $table->foreign('fk_policy')->references('id')->on('policies');
            $table->index('fk_policy');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("dependents", function(Blueprint $table){
            $table->dropForeign("dependents_fk_relationship_foreign");
            $table->dropIndex("dependents_fk_relationship_index");

            $table->dropForeign("dependents_fk_policy_foreign");
            $table->dropIndex("dependents_fk_policy_index");
        });

        Schema::dropIfExists('dependents');
    }
};
