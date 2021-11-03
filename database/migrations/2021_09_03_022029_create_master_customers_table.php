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
            $table->string('cust_type')->nullable();
            $table->string('name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('birthdate')->nullable();
            //corps
            $table->string('npwp')->nullable();;
            $table->string('no_izin_usaha')->nullable();;

            //addresses
            $table->string('current_address_type')->nullable();
            $table->string('current_address')->nullable();
            $table->string('city')->nullable();
            $table->string('current_country_code')->nullable();
            $table->string('zip_code')->nullable();
            //phones
            $table->string('contact_type')->nullable();
            $table->string('communication_type')->nullable();
            $table->string('country_prefix')->nullable();
            $table->string('phone_number')->nullable();
            //rekenings
            $table->string('cif')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_status')->nullable();
            $table->string('account_num')->nullable();
            $table->string('pjk_id')->nullable();
            //atms
            $table->string('card_num')->nullable();
            //identifications
            $table->string('id_type')->nullable();;
            $table->string('id_num')->nullable();;
            $table->string('issue_date')->nullable();;
            $table->string('expiry_date')->nullable();;
            $table->string('issued_by')->nullable();;
            $table->string('issued_country_code')->nullable();;

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
