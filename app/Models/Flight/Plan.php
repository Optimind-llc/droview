<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function type()
    {
        return $this->belongsTo('App\Models\Flight\Type');
    }

    public function place()
    {
        return $this->belongsTo('App\Models\Flight\Place');
    }
}
