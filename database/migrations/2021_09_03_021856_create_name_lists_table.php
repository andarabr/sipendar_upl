<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNameListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('name_lists', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->nullable();
            $table->string('list_id')->nullable();
            $table->string('kode_watchlist')->nullable();
            $table->string('jenis_pelaku')->nullable();
            $table->string('name');
            $table->string('id_num')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('birthdate')->nullable();
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
        Schema::dropIfExists('name_lists');
    }
}
