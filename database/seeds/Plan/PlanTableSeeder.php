<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Flight\Plan;
use App\Models\Flight\type;
use App\Models\Flight\Place;

class PlanTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('plans')->delete();

		$type_ids = Type::get(['id'])->toArray();
		$place_ids = Place::get(['id'])->toArray();

		foreach ($type_ids as $type_id) {
			foreach ($place_ids as $place_id) {
				Plan::create(['type_id' => $type_id['id'], 'place_id' => $place_id['id']]);
			}
		}

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}