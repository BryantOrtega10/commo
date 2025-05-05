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
        Schema::create('contract_uploads', function (Blueprint $table) {
            $table->id();
            $table->string("name");            
            $table->text("file_route");
            
            $table->tinyInteger("status")->comment("0 - Just uploaded, 1 - Linking, 2 - With errors, 3 - Completed")->default(0)->nullable();

            $table->text('headers')->comment("Json array")->nullable();
            $table->dateTime("processing_start_date")->nullable();
            $table->dateTime("processing_end_date")->nullable();
            
            $table->bigInteger("fk_commission_upload")->unsigned()->nullable();
            $table->foreign('fk_commission_upload')->references('id')->on('commission_uploads');
            $table->index('fk_commission_upload');
            
            $table->bigInteger("fk_carrier")->unsigned()->nullable();
            $table->foreign('fk_carrier')->references('id')->on('carriers');
            $table->index('fk_carrier');

            $table->bigInteger("fk_template")->unsigned()->nullable();
            $table->foreign('fk_template')->references('id')->on('templates');
            $table->index('fk_template');

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
        Schema::table("contract_uploads", function(Blueprint $table){
            $table->dropForeign("contract_uploads_fk_commission_upload_foreign");
            $table->dropIndex("contract_uploads_fk_commission_upload_index");
            $table->dropForeign("contract_uploads_fk_carrier_foreign");
            $table->dropIndex("contract_uploads_fk_carrier_index");
            $table->dropForeign("contract_uploads_fk_template_foreign");
            $table->dropIndex("contract_uploads_fk_template_index");
            $table->dropForeign("contract_uploads_fk_entry_user_foreign");
            $table->dropIndex("contract_uploads_fk_entry_user_index");
        });


        Schema::dropIfExists('contract_uploads');
    }
};
