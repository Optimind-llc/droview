<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Flight\Type;

class TypeTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::table('types')->delete();

		Type::create(['name' => 'LEDチカチカ', 'en' => 'free']);
		Type::create(['name' => '植物育成', 'en' => 'program']);
		Type::create(['name' => '魚鑑賞', 'en' => 'game']);

		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}