<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvironmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environments', function ($table){
            $table->increments('id');
            $table->string('browser');
            $table->string('ip_address');
            $table->integer('up_load');
            $table->integer('down_load');
            $table->string('connection_method');
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
        Schema::dropIfExists( 'environments' );
    }
}
