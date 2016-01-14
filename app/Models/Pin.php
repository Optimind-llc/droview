<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
	protected $fillable = ['pin', 'numberOfTickets'];
}