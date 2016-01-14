<?php

namespace App\Services\Flight;

use Carbon\Carbon;
use App\Models\Flight\Plan;
use App\Models\Flight\Environment;

trait GetReservations {

    public function getReservations()
    {
		$limit = Carbon::now()->subMinute(config('flight.flight_time'));
		$flights = \Auth::user()
			->flights()
			->where('flight_at', '>=', $limit)
			->get(['plan_id','flight_at'])
			->toArray();

        $i = 0;
        foreach ($flights as $flight) {
            $plan_id = $flight['plan_id'];

            $type = Plan::findOrFail($plan_id)
            	->type
            	->name;
            $flights[$i]['type'] = $type;

            $place = Plan::findOrFail($plan_id)
            	->place
            	->name;
            $flights[$i]['place'] = $place;

            $environment_id = $flight['pivot']['environment_id'];
            $environment = Environment::findOrFail($environment_id)->toArray();
            $flights[$i]['env'] = $environment;
            unset($flights[$i]['plan_id']);
            $i ++;
        }
        return $flights;
    }
}
