<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //飛行タイプ（自由飛行・プログラミング・ゲーム）
        //Webページのデザインを大幅に変更する必要があるので、基本的には変更不可
        Schema::create('types', function ($table){
            $table->increments('id');
            $table->string('name');
            $table->string('en');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //飛行場所
        Schema::create('places', function ($table){
            $table->increments('id');
            $table->string('name');
            $table->string('path');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //飛行プラン
        //飛行タイプ・飛行場所を指定
        Schema::create('plans', function ($table){
            $table->increments('id');
            $table->boolean('active');
            $table->string('description')->nullable();
            $table->integer('type_id')->unsigned()->index();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->integer('place_id')->unsigned()->index();
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'plans' );
        Schema::dropIfExists( 'types' );
        Schema::dropIfExists( 'places' );
    }
}
