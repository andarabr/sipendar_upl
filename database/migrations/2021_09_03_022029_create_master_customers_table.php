<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_customers', function (Blueprint $table) {
            $table->id();
            //retail
            $table->char('cust_type', 1);
            $table->string('name');
            $table->char('country_code', 2);
            $table->string('birthplace')->nullable();
            $table->string('birthdate');
            //corps
            $table->string('npwp')->nullable();;
            $table->string('no_izin_usaha')->nullable();;

            //addresses
            $table->string('current_address_type');
            $table->string('current_address');
            $table->string('city');
            $table->char('current_country_code', 2);
            $table->char('zip_code', 5);
            //phones
            $table->string('contact_type');
            $table->string('communication_type');
            $table->char('country_prefix', 2);
            $table->char('phone_number', 13);
            //rekenings
            $table->string('cif');
            $table->string('account_type');
            $table->string('account_status');
            $table->string('account_num');
            $table->string('pjk_id')->nullable();
            //atms
            $table->string('card_num');
            //identifications
            $table->string('id_type')->nullable();;
            $table->char('id_num', 16)->nullable();;
            $table->string('issue_date')->nullable();;
            $table->string('expiry_date')->nullable();;
            $table->string('issued_by')->nullable();;
            $table->char('issued_country_code', 2)->nullable();;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_customers');
    }
}
