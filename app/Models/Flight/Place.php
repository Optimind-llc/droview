<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	public function plans()
	{
		return $this->hasMany('App\Models\Flight\Plan');
	}

	public function img()
	{
		return $this->belongsTo('App\Models\Flight\Img');
	}
}
