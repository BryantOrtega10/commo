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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("route");
            $table->bigInteger("fk_customer")->unsigned()->nullable();
            $table->foreign('fk_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->index('fk_customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("files", function(Blueprint $table){
            $table->dropForeign("files_fk_customer_foreign");
            $table->dropIndex("files_fk_customer_index");
        });

        Schema::dropIfExists('files');
    }
};
