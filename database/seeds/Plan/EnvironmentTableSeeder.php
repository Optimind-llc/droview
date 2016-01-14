<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Flight\Environment;

class  EnvironmentTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('environments')->delete();

		Environment::create([
			'browser' => 'chrome',
			'ip_address' => '49.106.193.92',
			'up_load' => '53200',
			'down_load' => '104100',
			'connection_method' => 'tcp'
		]);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}