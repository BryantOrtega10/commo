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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger("fk_business_type")->unsigned()->nullable();
            $table->foreign('fk_business_type')->references('id')->on('business_types')->onDelete('cascade');
            $table->index('fk_business_type');

            $table->string("first_name")->nullable();
            $table->string("middle_initial")->nullable();            
            $table->string("last_name")->nullable();
            
            $table->bigInteger("fk_suffix")->unsigned()->nullable();
            $table->foreign('fk_suffix')->references('id')->on('suffixes')->onDelete('cascade');
            $table->index('fk_suffix');

            $table->date("date_birth")->nullable();
            $table->string("ssn")->nullable();

            $table->bigInteger("fk_gender")->unsigned()->nullable();
            $table->foreign('fk_gender')->references('id')->on('genders')->onDelete('cascade');
            $table->index('fk_gender');

            $table->bigInteger("fk_marital_status")->unsigned()->nullable();
            $table->foreign('fk_marital_status')->references('id')->on('marital_status')->onDelete('cascade');
            $table->index('fk_marital_status');

            $table->string("email")->nullable();
            $table->string("address")->nullable();
            $table->string("address_2")->nullable();

            $table->bigInteger("fk_county")->unsigned()->nullable();
            $table->foreign('fk_county')->references('id')->on('counties')->onDelete('cascade');
            $table->index('fk_county');

            $table->string("city")->nullable();
            $table->string("zip_code")->nullable();

            $table->string("phone")->nullable();
            $table->string("phone_2")->nullable();

            $table->bigInteger("fk_agent")->unsigned()->nullable();

            $table->bigInteger("fk_registration_s")->unsigned()->nullable();
            $table->foreign('fk_registration_s')->references('id')->on('registration_sources')->onDelete('cascade');
            $table->index('fk_registration_s');

            $table->bigInteger("fk_customer")->unsigned()->nullable();
            $table->foreign('fk_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->index('fk_customer');

            $table->bigInteger("fk_entry_user")->unsigned();
            $table->foreign('fk_entry_user')->references('id')->on('users')->onDelete('cascade');
            $table->index('fk_entry_user');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("customers", function(Blueprint $table){
            $table->dropForeign("customers_fk_business_type_foreign");
            $table->dropIndex("customers_fk_business_type_index");

            $table->dropForeign("customers_fk_suffix_foreign");
            $table->dropIndex("customers_fk_suffix_index");
            
            $table->dropForeign("customers_fk_gender_foreign");
            $table->dropIndex("customers_fk_gender_index");
            
            $table->dropForeign("customers_fk_marital_status_foreign");
            $table->dropIndex("customers_fk_marital_status_index");
            
            $table->dropForeign("customers_fk_county_foreign");
            $table->dropIndex("customers_fk_county_index");
            
            $table->dropForeign("customers_fk_registration_s_foreign");
            $table->dropIndex("customers_fk_registration_s_index");
            
            $table->dropForeign("customers_fk_customer_foreign");
            $table->dropIndex("customers_fk_customer_index");            

        });

        
        Schema::dropIfExists('customers');
    }
};
