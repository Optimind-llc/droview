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
            $table->integer('img_id')->unsigned()->index();
            $table->foreign('img_id')->references('id')->on('imgs');
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

        define('DB_HOST', env('DB_HOST', '172.17.0.2'));
        define('DB_NAME', env('DB_DATABASE', 'laravel'));
        define('DB_USER', env('DB_USERNAME', 'root'));
        define('DB_PASSWORD', env('DB_PASSWORD', 'root'));

        // 文字化け対策
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

        // PHPのエラーを表示するように設定
        error_reporting(E_ALL & ~E_NOTICE);

        // データベースの接続
        try {
             $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
             $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
             echo $e->getMessage();
             echo "aa";
             exit;
        }

        $dbh->exec("ALTER TABLE imgs CHANGE data data MEDIUMBLOB;");
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
