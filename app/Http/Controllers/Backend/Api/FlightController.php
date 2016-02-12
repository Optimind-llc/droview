<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
//Models
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Backend\Flight\TimetableRequest;

class FlightController extends Controller
{

    public function index()
    {
        return view('backend.flight.index', compact('tickets', 'sum'));
    }

    public function timetables(TimetableRequest $request)
    {
    	$timetables = array();
    	$plan_id = $this->getPlanId($request->type, $request->place);

    	foreach ($request->days as $n)
    	{
    		$day = $this->getCarbon($n);
    		array_push($timetables, $this->getTimetable($plan_id, $day));
    	}

    	return \Response::json($timetables);
    }

    protected function getPlanId($type_id = 1, $place_id = 1)
    {
        $plan_id = Plan::where('type_id', '=', $type_id)
            ->where('place_id', '=', $place_id)
            ->firstOrFail(['id']);

        if(!$plan_id) {
        	throw new NotFoundException('server.plan.notFound');
        }

        return $plan_id->id;
    }

    protected function getTimetable($plan_id, $day)
    {
    	return array(
    		$this->getDate($day),
    		$this->getFlight($plan_id, $day)
    	);
    }

    protected function getFlight($plan_id = 1, $day = false)
    {
    	if (!$day) $day = $this->getCarbon();

        $flights = Flight::with(['users' => function ($query) {
			$query->select('users.id');
		}])
    	//->where('flight_at', '>', Carbon::now()->addMinute(config('flight.reservation_period')))
        ->where('flight_at', '>=', $day)
        ->where('flight_at', '<', $day->copy()->addDay())
        ->where('plan_id', '=', $plan_id)
        ->get(['id', 'flight_at', 'numberOfDrones']);

        return $flights;
    }

    protected function getDate($day = false)
    {
    	if (!$day) $day = $this->getCarbon();
        return array($day->month, $day->day, $day->dayOfWeek);
    }

    protected function getCarbon($num = 0)
    {
        return Carbon::now()->today()->addDay($num);
    }
}
