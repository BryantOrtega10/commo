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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("type")->comment("1 - Note, 2- Mail, 3 - Call, 4 - Meeting, 5 - Task");
            $table->text("description");
            $table->string("task_name")->nullable();
            $table->dateTime("expiration_date")->nullable();
            $table->tinyInteger("priority")->nullable()->comment("1 - Low, 2 - Mid, 3 - High");
            
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
        Schema::dropIfExists('activities');
    }
};
