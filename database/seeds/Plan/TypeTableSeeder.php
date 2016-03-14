<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('types')->delete();

		$types= [
			[
				'name' => 'ドローン自由飛行',
				'en' => 'led',
				'description' => '遠隔でドローンを自由に操縦することができます。ドローンに設置されているカメラからの映像がリアルタイムで配信されてくるので、まるで空を飛んでいるかのような体験を味わえます。'
			],
			[
				'name' => 'ラジコンカーレース',
				'en' => 'vege',
				'description' => '遠隔でラジコンカーを操縦することができます。リアルタイムで他のプレイヤーとレースを楽しめます。'
			]
		];

		DB::table('types')->insert($types);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}


