<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlightPlanTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->call(TypeTableSeeder::class);
		$this->call(PlaceTableSeeder::class);
		$this->call(PlanTableSeeder::class);
		$this->call(EnvironmentTableSeeder::class);
		$this->call(FlightTableSeeder::class);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}