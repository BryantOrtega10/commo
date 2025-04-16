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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("description");

            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers');
            $table->index('fk_carrier');

            $table->bigInteger("fk_business_segment")->unsigned()->nullable();
            $table->foreign('fk_business_segment')->references('id')->on('business_segments');
            $table->index('fk_business_segment');

            $table->bigInteger("fk_business_type")->unsigned()->nullable();
            $table->foreign('fk_business_type')->references('id')->on('business_types');
            $table->index('fk_business_type');

            $table->bigInteger("fk_product_type")->unsigned()->nullable();
            $table->foreign('fk_product_type')->references('id')->on('product_types');
            $table->index('fk_product_type');

            $table->bigInteger("fk_plan_type")->unsigned()->nullable();
            $table->foreign('fk_plan_type')->references('id')->on('plan_types');
            $table->index('fk_plan_type');

            $table->bigInteger("fk_tier")->unsigned()->nullable();
            $table->foreign('fk_tier')->references('id')->on('tiers');
            $table->index('fk_tier');

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
        Schema::table("products", function(Blueprint $table){
            $table->dropForeign("products_fk_carrier_foreign");
            $table->dropIndex("products_fk_carrier_index");
            $table->dropForeign("products_fk_business_segment_foreign");
            $table->dropIndex("products_fk_business_segment_index");
            $table->dropForeign("products_fk_business_type_foreign");
            $table->dropIndex("products_fk_business_type_index");
            $table->dropForeign("products_fk_product_type_foreign");
            $table->dropIndex("products_fk_product_type_index");
            $table->dropForeign("products_fk_plan_type_foreign");
            $table->dropIndex("products_fk_plan_type_index");
            $table->dropForeign("products_fk_tier_foreign");
            $table->dropIndex("products_fk_tier_index");
            $table->dropForeign("products_fk_entry_user_foreign");
            $table->dropIndex("products_fk_entry_user_index");
        });

        Schema::dropIfExists('products');
    }
};
