<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Flight\Place;

class PlaceTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('places')->delete();

		$places= [
			['name' => 'サンプル1', 'path' => '/img/place/1.jpg'],
			['name' => 'サンプル2', 'path' => '/img/place/2.jpg'],
			['name' => 'サンプル3', 'path' => '/img/place/3.jpg'],
			['name' => 'サンプル4', 'path' => '/img/place/4.jpg'],
			['name' => 'サンプル5', 'path' => '/img/place/5.jpg'],
			['name' => 'サンプル6', 'path' => '/img/place/6.jpg'],
			['name' => 'サンプル7', 'path' => '/img/place/7.jpg'],
			['name' => 'サンプル8', 'path' => '/img/place/8.jpg'],
			['name' => 'サンプル9', 'path' => '/img/place/9.jpg'],
			['name' => 'サンプル10', 'path' => '/img/place/10.jpg'],
			['name' => 'サンプル11', 'path' => '/img/place/11.jpg'],
			['name' => 'サンプル12', 'path' => '/img/place/12.jpg'],
			['name' => 'サンプル13', 'path' => '/img/place/13.jpg'],
			['name' => 'サンプル14', 'path' => '/img/place/14.jpg'],
			['name' => 'サンプル15', 'path' => '/img/place/15.jpg'],
			['name' => 'サンプル16', 'path' => '/img/place/16.jpg'],
			['name' => 'サンプル17', 'path' => '/img/place/17.jpg'],
			['name' => 'サンプル18', 'path' => '/img/place/18.jpg'],
			['name' => 'サンプル19', 'path' => '/img/place/19.jpg'],
			['name' => 'サンプル20', 'path' => '/img/place/20.jpg'],
		];

		DB::table('places')->insert($places);


		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}