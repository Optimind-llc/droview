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
			['name' => '水田１', 'img_id' => '1'],
			['name' => '夕焼けの孤島', 'img_id' => '2'],
			['name' => '南国の海', 'img_id' => '3'],
			['name' => 'アリの行進', 'img_id' => '4'],
			['name' => '海岸', 'img_id' => '5'],
			['name' => '木と空', 'img_id' => '6'],
			['name' => '並木道', 'img_id' => '7'],
			['name' => '花', 'img_id' => '8'],
			['name' => '城壁都市', 'img_id' => '9'],
			['name' => '夜の富士山', 'img_id' => '10'],
			['name' => '凍った岸', 'img_id' => '11'],
			['name' => '原生林', 'img_id' => '12'],
			['name' => '水田２', 'img_id' => '13'],
			['name' => '冬の川', 'img_id' => '14'],
			['name' => '南国の畑', 'img_id' => '15'],
			['name' => '都会の紅葉', 'img_id' => '16'],
			['name' => '水田３', 'img_id' => '17'],
			['name' => '夕日と少女', 'img_id' => '18'],
			['name' => '牧場', 'img_id' => '19'],
			['name' => '砂漠の街', 'img_id' => '20'],
		];

		DB::table('places')->insert($places);


		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}