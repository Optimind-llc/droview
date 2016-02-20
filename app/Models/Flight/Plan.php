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

    public function flights()
    {
        return $this->hasMany('App\Models\Flight\Flight');
    }

    // public function canBeDeleted(int $plan_id)
    // {
    //     $this->with([
    //         'flights' => function ($query) {
    //             $query->with([
    //                 'users' => function ($query) {
    //                     $query->select(['users.id']);
    //                 }
    //             ])
    //             ->select('flights.id', 'flights.plan_id');
    //         }
    //     ])
    //     ->find($plan_id);
    // }
}
