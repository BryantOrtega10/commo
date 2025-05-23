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
        Schema::dropIfExists('member_types');
        Schema::dropIfExists('policy_agent_number_types');
        Schema::dropIfExists('client_sources');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->integer("sort_order")->default(-1)->nullable();
            $table->tinyInteger("status")->default(1)->comment("0 Inactive, 1 Active")->nullable();
            $table->timestamps();
        });

        Schema::create('policy_agent_number_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->integer("sort_order")->default(-1)->nullable();
            $table->tinyInteger("status")->default(1)->comment("0 Inactive, 1 Active")->nullable();
            $table->timestamps();
        });

        Schema::create('client_sources', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->integer("sort_order")->default(-1)->nullable();
            $table->tinyInteger("status")->default(1)->comment("0 Inactive, 1 Active")->nullable();
            $table->timestamps();
        });

    }
};
