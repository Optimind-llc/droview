<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	public function places()
	{
		return $this
			->belongsToMany('App\Models\Flight\Place', 'plans');
	}
}
