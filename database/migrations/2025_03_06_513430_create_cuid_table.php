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
        Schema::create('cuids', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            
            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers')->onDelete('cascade');
            $table->index('fk_carrier');

            $table->bigInteger("fk_business_segment")->unsigned()->nullable();
            $table->foreign('fk_business_segment')->references('id')->on('business_segments')->onDelete('cascade');
            $table->index('fk_business_segment');

            $table->date("validation_date")->nullable();
            $table->text("validation_note")->nullable();

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

        Schema::table("cuids", function(Blueprint $table){
            $table->dropForeign("cuids_fk_carrier_foreign");
            $table->dropIndex("cuids_fk_carrier_index");
            
            $table->dropForeign("cuids_fk_business_segment_foreign");
            $table->dropIndex("cuids_fk_business_segment_index");
        });

        Schema::dropIfExists('cuids');
    }
};
