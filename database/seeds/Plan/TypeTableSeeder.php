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
				'name' => 'LEDチカチカ',
				'en' => 'led',
				'description' => 'もう朝が講演家は何しろその下宿なないまでに取らていべきをは矛盾くっついないあっが、実際にはいうたなたた。懐手をしですのはざっと昔を今にまいませだ。'
			],
			[
				'name' => '植物育成',
				'en' => 'vege',
				'description' => 'それはいよいよ存在ののから皆矛盾はしといでなだたて、五五の後れにそれほどすまでしという講演ですて、つまりこの自分のなおのことが作るられが、それかにあなたの先輩に合点に上って下さらうのですないと注意感ずるが話失っおきらしくませ。'
			],
			[
				'name' => '魚鑑賞',
				'en' => 'aqua',
				'description' => 'しかしそう二年は文学の移ろて、前をもとより気に入るたろらしいと構うて、ないですですがすると皆相当を云わなでし。口の今で、そんな男と事実でしばかり、その間ごろをそう場合五三一通りにありじゃの個人に、私か繰返しで接近に行っうほかない。'
			],
		];

		DB::table('types')->insert($types);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}


