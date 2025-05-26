<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('CREATE TRIGGER after_statement_items_insert
        AFTER INSERT ON statement_items
        FOR EACH ROW
        BEGIN
            DECLARE f_number_policies DECIMAL(10,2);
            DECLARE f_total DECIMAL(10,2);

            SELECT COUNT(*), COALESCE(SUM(total), 0) INTO f_number_policies, f_total
            FROM statement_items
            WHERE fk_statement = NEW.fk_statement;

    
            UPDATE statements SET number_policies = f_number_policies, total = f_total where id = NEW.fk_statement;

        END');


        DB::unprepared('CREATE TRIGGER after_statement_items_delete
        AFTER DELETE ON statement_items
        FOR EACH ROW
        BEGIN
            DECLARE f_number_policies DECIMAL(10,2);
            DECLARE f_total DECIMAL(10,2);

            SELECT COUNT(*), COALESCE(SUM(total), 0) INTO f_number_policies, f_total
            FROM statement_items
            WHERE fk_statement = OLD.fk_statement;
    
            UPDATE statements SET number_policies = f_number_policies, total = f_total where id = OLD.fk_statement;

        END');

        DB::unprepared('CREATE TRIGGER after_statement_items_update
        AFTER UPDATE ON statement_items
        FOR EACH ROW
        BEGIN
            DECLARE f_number_policies DECIMAL(10,2);
            DECLARE f_total DECIMAL(10,2);

            SELECT COUNT(*), COALESCE(SUM(comp_amount), 0) INTO f_number_policies, f_total
            FROM statement_items
            WHERE fk_statement = NEW.fk_statement;

    
            UPDATE statements SET number_policies = f_number_policies, total = f_total where id = NEW.fk_statement;

        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_statement_items_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_statement_items_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_statement_items_update');
    }
};
