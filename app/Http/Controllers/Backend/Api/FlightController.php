<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\Flight\FlightContract;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Backend\Flight\TimetableRequest;
use App\Http\Requests\Api\Backend\Flight\PlanRequest;

class FlightController extends Controller
{
    /**
     * @var FlightContract
     */
    protected $flights;

    /**
     * @param FlightContract $flights
     */
    public function __construct(FlightContract $flights)
    {
        $this->flights = $flights;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function timetables(TimetableRequest $request)
    {
    	$timetables = [];
        $plan_id = $request->plan_id;
        $range = $request->range;
        $timestamp = $request->timestamp;

        for ($i = $range[0]; $i <= $range[1]; $i++) {
            array_push(
                $timetables,
                $this->flights->getTimetable($plan_id, $timestamp, $i)
            );
        }

        $response = [
            'config' => $this->flights->getConfig(),
            'timetables' => $timetables
        ];

        return \Response::json($response, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function open(PlanRequest $request)
    {
        $plan_id = $request->plan_id;
        $timestamp = $request->timestamp;
        $period = $request->period;

        if (!$this->flights->create($plan_id, $timestamp, $period)) {
            throw new NotFoundException('flight.open.fail');
        }

        $response = [
            'config' => $this->flights->getConfig(),
            'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
        ];

        return \Response::json($response, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(PlanRequest $request)
    {
        $id = $request->id;
        $plan_id = $request->plan_id;
        $timestamp = $request->timestamp;

        if (!$this->flights->destroy($id)) {
            throw new NotFoundException('flight.close.fail');
        }

        $response = [
            'config' => $this->flights->getConfig(),
            'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
        ];

        return \Response::json($response, 200);
    }
}


