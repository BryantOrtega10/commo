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
        Schema::create('commission_uploads', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("file_route");
            $table->integer("rows_uploaded")->unsigned()->default(0)->nullable();
            $table->integer("rows_processed")->unsigned()->default(0)->nullable();
            $table->date("statement_date")->nullable();
            $table->dateTime("processing_start_date")->nullable();
            $table->dateTime("processing_end_date")->nullable();
            $table->tinyInteger("status")->comment("0 - Uploading, 1 - Uploaded, 2 - Linking, 3 - With errors, 4 - Completed")->default(0)->nullable();
            $table->date("check_date")->nullable();

            $table->bigInteger("fk_template")->unsigned();
            $table->foreign('fk_template')->references('id')->on('templates');
            $table->index('fk_template');

            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers');
            $table->index('fk_carrier');

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
        Schema::table("commission_uploads", function(Blueprint $table){
            $table->dropForeign("commission_uploads_fk_carrier_foreign");
            $table->dropIndex("commission_uploads_fk_carrier_index");

            $table->dropForeign("commission_uploads_fk_entry_user_foreign");
            $table->dropIndex("commission_uploads_fk_entry_user_index");

            $table->dropForeign("commission_uploads_fk_template_foreign");
            $table->dropIndex("commission_uploads_fk_template_index");
        });

        Schema::dropIfExists('commission_uploads');
    }
};
