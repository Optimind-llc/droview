<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	//設定しないとinsetでエラーが起こることがある
	protected $guarded = ['id'];

	public function users()
	{
		return $this->belongsTo('App\Models\Access\User\User');
	}
}
