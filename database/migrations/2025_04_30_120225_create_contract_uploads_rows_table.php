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
        Schema::create('contract_uploads_rows', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger("status")->comment("0 - Unlinked, 1 - Linking, 2 - Linked")->default(0)->nullable();
            $table->text("note")->nullable();
            $table->text('data')->comment("Json format")->nullable();
            
            $table->bigInteger("fk_comm_upload_rows")->unsigned()->nullable();
            $table->foreign('fk_comm_upload_rows')->references('id')->on('commission_upload_rows');
            $table->index('fk_comm_upload_rows');           

            $table->bigInteger("fk_contract_uploads")->unsigned();
            $table->foreign('fk_contract_uploads')->references('id')->on('contract_uploads');
            $table->index('fk_contract_uploads');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("contract_uploads_rows", function(Blueprint $table){
            $table->dropForeign("contract_uploads_rows_fk_comm_upload_rows_foreign");
            $table->dropIndex("contract_uploads_rows_fk_comm_upload_rows_index");
            $table->dropForeign("contract_uploads_rows_fk_contract_uploads_foreign");
            $table->dropIndex("contract_uploads_rows_fk_contract_uploads_index");           
        });

        Schema::dropIfExists('contract_uploads_rows');
    }
};
