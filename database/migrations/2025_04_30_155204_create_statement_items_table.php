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
        Schema::create('statement_items', function (Blueprint $table) {
            $table->id();

            $table->date("check_date")->nullable();

            $table->bigInteger("fk_statement")->unsigned();
            $table->foreign('fk_statement')->references('id')->on('statements');
            $table->index('fk_statement');

            $table->tinyInteger("agent_type")->unsigned()->comment("0 - Writting Agent, 1 - Mentor Agent, 2 - Override Agent, 3 - Carrier Agent")->nullable();
            
            $table->decimal("flat_rate")->nullable();
            $table->tinyInteger("rate_type")->comment("0 - Percentage, 1 - Flat Rate, 2 - Flat Rate per member")->nullable();
            $table->decimal("comp_amount");

            $table->bigInteger("fk_commission_rate")->unsigned()->nullable();
            $table->foreign('fk_commission_rate')->references('id')->on('commission_rates')->nullOnDelete();
            $table->index('fk_commission_rate');

            $table->bigInteger("fk_commission_transaction")->unsigned()->nullable();
            $table->foreign('fk_commission_transaction')->references('id')->on('commission_transactions')->onDelete("cascade");
            $table->index('fk_commission_transaction');

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
        Schema::table("statement_items", function(Blueprint $table){
            
            $table->dropForeign("statement_items_fk_statement_foreign");
            $table->dropIndex("statement_items_fk_statement_index");
            $table->dropForeign("statement_items_fk_commission_rate_foreign");
            $table->dropIndex("statement_items_fk_commission_rate_index");
            $table->dropForeign("statement_items_fk_commission_transaction_foreign");
            $table->dropIndex("statement_items_fk_commission_transaction_index");
            $table->dropForeign("statement_items_fk_entry_user_foreign");
            $table->dropIndex("statement_items_fk_entry_user_index");
        });
        Schema::dropIfExists('statement_items');
    }
};
