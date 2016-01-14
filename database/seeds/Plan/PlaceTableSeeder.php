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

		Place::create(['name' => '飼育場', 'path' => '/img/led/led.jpg']);
		Place::create(['name' => '水槽', 'path' => '/img/led/aquarium.jpg']);
		Place::create(['name' => '試験場', 'path' => '/img/led/plant.jpg']);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}