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
        Schema::create('commission_upload_rows', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("status")->comment("0 - Unlinked, 1 - Linking, 2 - Linked")->default(0)->nullable();
            $table->text("notes")->nullable();
            $table->text('data')->comment("Json format")->nullable();

            $table->bigInteger("fk_commission_upload")->unsigned();
            $table->foreign('fk_commission_upload')->references('id')->on('commission_uploads');
            $table->index('fk_commission_upload');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("commission_upload_rows", function(Blueprint $table){
            $table->dropForeign("commission_upload_rows_fk_commission_upload_foreign");
            $table->dropIndex("commission_upload_rows_fk_commission_upload_index");
        });

        Schema::dropIfExists('commission_upload_rows');
    }
};
