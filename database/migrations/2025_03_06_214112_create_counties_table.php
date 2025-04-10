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
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->integer("sort_order")->default(-1)->nullable();
            $table->tinyInteger("status")->default(1)->comment("0 Inactive, 1 Active")->nullable();

            $table->bigInteger("fk_state")->unsigned()->nullable();
            $table->foreign('fk_state')->references('id')->on('states');
            $table->index('fk_state');
            
            $table->bigInteger("fk_region")->unsigned()->nullable();
            $table->foreign('fk_region')->references('id')->on('regions');
            $table->index('fk_region');
            
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table("counties", function(Blueprint $table){
            $table->dropForeign("counties_fk_state_foreign");
            $table->dropIndex("counties_fk_state_index");

            $table->dropForeign("counties_fk_region_foreign");
            $table->dropIndex("counties_fk_region_index");
        });


        Schema::dropIfExists('counties');
    }
};
