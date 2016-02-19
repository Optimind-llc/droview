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
			shuffle($place_ids);
			for ($i=0; $i < 6; $i++) { 
				Plan::create([
					'active' => true,
					'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mattis pretium massa.',
					'type_id' => $type_id['id'],
					'place_id' => $place_ids[$i]['id']
				]);
			}
		}

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}