<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //フライト情報
        Schema::create('flights', function ($table){
            $table->increments('id');
            $table->integer('plan_id')->unsigned()->index();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');;
            $table->integer('period');
            $table->dateTime('flight_at');
            $table->tinyInteger('numberOfDrones');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['plan_id', 'flight_at']);
        });
    
        Schema::create('flight_user', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('flight_id')->unsigned()->index();
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->string('browser');
            $table->string('ip_address');
            $table->integer('up_load');
            $table->integer('down_load');
            $table->string('connection_method');
            $table->tinyInteger('status');
            $table->string('jwt');
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
        Schema::dropIfExists( 'flight_user' );
        Schema::dropIfExists( 'flights' );
    }
}
