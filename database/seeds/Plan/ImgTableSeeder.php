<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImgTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('imgs')->delete();

		$imgs= [
			['data' => Storage::get('/place/1.jpg')],
			['data' => Storage::get('/place/2.jpg')],
			['data' => Storage::get('/place/3.jpg')],
			['data' => Storage::get('/place/4.jpg')],
			['data' => Storage::get('/place/5.jpg')],
			['data' => Storage::get('/place/6.jpg')],
			['data' => Storage::get('/place/7.jpg')],
			['data' => Storage::get('/place/8.jpg')],
			['data' => Storage::get('/place/9.jpg')],
			['data' => Storage::get('/place/10.jpg')],
			['data' => Storage::get('/place/11.jpg')],
			['data' => Storage::get('/place/12.jpg')],
			['data' => Storage::get('/place/13.jpg')],
			['data' => Storage::get('/place/14.jpg')],
			['data' => Storage::get('/place/15.jpg')],
			['data' => Storage::get('/place/16.jpg')],
			['data' => Storage::get('/place/17.jpg')],
			['data' => Storage::get('/place/18.jpg')],
			['data' => Storage::get('/place/19.jpg')],
			['data' => Storage::get('/place/20.jpg')],
		];

		DB::table('imgs')->insert($imgs);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}