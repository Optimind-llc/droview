<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;

class FlightUser extends Model
{

	protected $table = 'flight_user';
    protected $fillable = ['user_id', 'flight_id'];

	public function user()
	{
		return $this->hasOne('App\Models\Access\User\User','id','user_id');
	}

	public function flight()
	{
		return $this->belongsTo('App\Models\Flight\Flight','id','flight_id');
	}
}