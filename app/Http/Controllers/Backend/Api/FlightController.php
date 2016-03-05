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
        $plan_id = $request->plan_id;
        $range = $request->range;
        $timestamp = $request->timestamp;

        $timetables = [];

        for ($i = $range[0]; $i <= $range[1]; $i++) {
            $timetables[] = $this->flights->getTimetable($plan_id, $timestamp, $i);
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
    public function update(PlanRequest $request)
    {
        $flight = $this->flights->update($request->id, $request->capacity);

        $plan_id = $flight->plan_id;
        $timestamp = $flight->flight_at->timestamp;

        $response = [
            'plan_id' => $plan_id,
            'date' => $this->flights->getDateObject($timestamp)->timestamp,
            'flight' => $flight
        ];

        return \Response::json($response, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function open(PlanRequest $request)
    {
        $flight = $this->flights->restore($request->id, $request->capacity);

        $plan_id = $flight->plan_id;
        $timestamp = $flight->flight_at->timestamp;

        // 1フライトのみ更新
        $response = [
            'plan_id' => $plan_id,
            'date' => $this->flights->getDateObject($timestamp)->timestamp,
            'flight' => $flight
        ];

        // 1日全部更新
        // $response = [
        //     'plan_id' => $plan_id,
        //     'config' => $this->flights->getConfig(),
        //     'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
        // ];

        return \Response::json($response, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(PlanRequest $request)
    {
        $flight = $this->flights->delete($request->id);

        $plan_id = $flight->plan_id;
        $timestamp = $flight->flight_at->timestamp;

        $response = [
            'plan_id' => $plan_id,
            'date' => $this->flights->getDateObject($timestamp)->timestamp,
            'flight' => $flight
        ];

        // 1日全部更新
        // $response = [
        //     'plan_id' => $plan_id,
        //     'config' => $this->flights->getConfig(),
        //     'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
        // ];

        return \Response::json($response, 200);
    }

    // /**
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function create(PlanRequest $request)
    // {
    //     $plan_id = $request->plan_id;
    //     $timestamp = $request->timestamp;
    //     $period = $request->period;

    //     if (!$this->flights->create($plan_id, $timestamp, $period)) {
    //         throw new NotFoundException('flight.open.fail');
    //     }

    //     $response = [
    //         'config' => $this->flights->getConfig(),
    //         'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
    //     ];

    //     return \Response::json($response, 200);
    // }

}


