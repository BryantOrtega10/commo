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
        Schema::create('product_alias', function (Blueprint $table) {
            $table->id();
            $table->string("alias");
            
            $table->bigInteger("fk_product")->unsigned()->nullable();
            $table->foreign('fk_product')->references('id')->on('products')->onDelete("cascade");
            $table->index('fk_product');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("product_alias", function(Blueprint $table){
            $table->dropForeign("product_alias_fk_product_foreign");
            $table->dropIndex("product_alias_fk_product_index");
        });
        Schema::dropIfExists('product_alias');
    }
};
