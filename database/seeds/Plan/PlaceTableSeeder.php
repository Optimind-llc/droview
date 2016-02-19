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

		Place::create(['name' => 'サンプル1', 'path' => '/img/place/1.jpg']);
		Place::create(['name' => 'サンプル2', 'path' => '/img/place/2.jpg']);
		Place::create(['name' => 'サンプル3', 'path' => '/img/place/3.jpg']);
		Place::create(['name' => 'サンプル4', 'path' => '/img/place/4.jpg']);
		Place::create(['name' => 'サンプル5', 'path' => '/img/place/5.jpg']);
		Place::create(['name' => 'サンプル6', 'path' => '/img/place/6.jpg']);
		Place::create(['name' => 'サンプル7', 'path' => '/img/place/7.jpg']);
		Place::create(['name' => 'サンプル8', 'path' => '/img/place/8.jpg']);
		Place::create(['name' => 'サンプル9', 'path' => '/img/place/9.jpg']);
		Place::create(['name' => 'サンプル10', 'path' => '/img/place/10.jpg']);
		Place::create(['name' => 'サンプル11', 'path' => '/img/place/11.jpg']);
		Place::create(['name' => 'サンプル12', 'path' => '/img/place/12.jpg']);
		Place::create(['name' => 'サンプル13', 'path' => '/img/place/13.jpg']);
		Place::create(['name' => 'サンプル14', 'path' => '/img/place/14.jpg']);
		Place::create(['name' => 'サンプル15', 'path' => '/img/place/15.jpg']);
		Place::create(['name' => 'サンプル16', 'path' => '/img/place/16.jpg']);
		Place::create(['name' => 'サンプル17', 'path' => '/img/place/17.jpg']);
		Place::create(['name' => 'サンプル18', 'path' => '/img/place/18.jpg']);
		Place::create(['name' => 'サンプル19', 'path' => '/img/place/19.jpg']);
		Place::create(['name' => 'サンプル20', 'path' => '/img/place/20.jpg']);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}