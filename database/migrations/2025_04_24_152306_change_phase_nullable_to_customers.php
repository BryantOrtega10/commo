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
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger("fk_phase")->unsigned()->nullable()->change();
            $table->bigInteger("fk_legal_basis")->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger("fk_phase")->unsigned()->nullable(false)->change();
            $table->bigInteger("fk_legal_basis")->unsigned()->nullable(false)->change();
        });
    }
};
