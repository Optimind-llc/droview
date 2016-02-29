<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('tickets')->delete();

		$users = DB::table('users')->select('id')->get();
		$tickets = [];
		foreach ($users as $user) {
			$tickets[] = [
				'user_id' => $user->id,
				'amount' => 10,
				'method' => 'seeds'
			];
		}

		DB::table('tickets')->insert($tickets);

		$user_id = DB::table('users')->first()->id;
		$tickets = [];
		for ($i=0; $i < 100; $i++) { 
			$tickets[] = [
				'user_id' => $user_id,
				'amount' => mt_rand (1, 10),
				'method' => 'seeds'
			];
		}

		DB::table('tickets')->insert($tickets);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}