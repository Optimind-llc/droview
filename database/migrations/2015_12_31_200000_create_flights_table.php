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
        //OPEN状態にするとフライトプランごとに作成される
        Schema::create('flights', function ($table){
            $table->increments('id');
            $table->integer('plan_id')->unsigned()->index();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');;
            $table->integer('period');
            $table->timestamp('flight_at');
            $table->tinyInteger('numberOfDrones');
            $table->timestamps();
        });
    
        Schema::create('flight_user', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('flight_id')->unsigned()->index();
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->integer('environment_id')->unsigned()->index();
            $table->foreign('environment_id')->references('id')->on('environments')->onDelete('cascade');            
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
