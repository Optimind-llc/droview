<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;

class FlightTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('flights')->delete();

		$ids = Plan::get(['id'])->toArray();

		$a = config('flight.start_at'); //開始時刻
		$b = config('flight.end_at');   //終了時刻
		$c = config('flight.time');     //1フライトの時間

		$flight_at = Carbon::tomorrow()->addhour($a);
		$n = ( $b - $a ) * 60 / $c;
		
		for ($i = 0; $i < 14; $i++) {
			for ($ii = 0; $ii < $n; $ii++) {
				foreach ($ids as $key => $id) {
					Flight::create([
						'plan_id' => $id["id"],
						'flight_at' => $flight_at,
						'numberOfDrones' => '1'
					]);
				}
				$flight_at->addMinute($c);
			}
			$sub = 24 - ( $b - $a );
			$flight_at->addHour($sub);
		}
	

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}