<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationLog extends Model
{
	protected $fillable = ['executor_id', 'user_id', 'flight_id', 'action'];
}