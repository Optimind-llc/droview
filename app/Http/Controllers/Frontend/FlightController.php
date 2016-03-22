<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
use App\Repositories\Frontend\Flight\FlightContract;
//Models
use App\Models\Access\User\User;
use App\Models\Flight\Flight;
use App\Models\ReservationLog;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Frontend\Flight\TimetableRequest;
use App\Http\Requests\Api\Frontend\Flight\PlanRequest;
//Jobs
use App\Jobs\SendConfirmReservationEmail;

use Illuminate\Support\Facades\DB;


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
    public function confirmReservation(PlanRequest $request)
    {
        $flight_id = $request['id'];

        DB::beginTransaction();

        $user = \Auth::user();
        // //未処理のチケット消費を実行        
        // $user->consumptionTicket();

        $flight = Flight::find($flight_id);
        //予約可能人数
        $numberOfDrones = $flight->numberOfDrones;
        //フライト開始時刻
        $flight_at = $flight->flight_at;
        //予約中のユーザー数
        $users = $flight->users()->lockForUpdate()->count();

        if ($flight_at->subMinute(config('flight.reservation_period'))->isPast()) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.isPast');         
        }

        if ($users >= $numberOfDrones) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.crowded');         
        }

        //完了していないフライト
        $unfinished = $user->flights()->where('status', '0')->lockForUpdate()->count();
        //同時刻に予約しているフライト
        $atTheSameTime = $user->flights()->where('flight_at', $flight_at)->count();
        //所持しているチケット数
        $tickets = $user->tickets()->sum('amount');

        if ($unfinished >= config('flight.limit_of_reservations')) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.overLimit');            
        }

        if ($unfinished >= $tickets) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.notEnoughTickets');            
        }

        if ($atTheSameTime >= 1) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.notEnoughTickets');            
        }

        DB::commit();

        // $start = $flight->getEnableAccessTime()->timestamp;
        // $end = $flight->getEnableAccessTime()->timestamp;

        $flight->toArray();

        $flight['user_name'] = $user->name;
        $flight['user_email'] = $user->email;
        // $flight['start'] = $start;
        // $flight['end'] = $end;

        $key = config('flight.jwt_key');
        $flight['jwt'] = JWT::encode($flight, $key);

        return \Response::json($flight, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function reserve(PlanRequest $request)
    {
        $flight_id = $request['id'];
        $jwt = $request['jwt'];

        DB::beginTransaction();

        $user = \Auth::user();

        $flight = Flight::find($flight_id);
        //予約可能人数
        $numberOfDrones = $flight->numberOfDrones;
        //フライト開始時刻
        $flight_at = $flight->flight_at;
        //予約中のユーザー数
        $users = $flight->users()->lockForUpdate()->count();

        if ($flight_at->subMinute(config('flight.reservation_period'))->isPast()) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.isPast');         
        } else {

        if ($users >= $numberOfDrones) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.crowded');         
        }  else {

        //完了していないフライト
        $unfinished = $user->flights()->where('status', '0')->lockForUpdate()->count();
        //同時刻に予約しているフライト
        $atTheSameTime = $user->flights()->where('flight_at', $flight_at)->lockForUpdate()->count();
        //所持しているチケット数
        $tickets = $user->tickets()->sum('amount');

        if ($unfinished >= config('flight.limit_of_reservations')) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.overLimit');            
        } else {

        if ($unfinished >= $tickets) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.notEnoughTickets');            
        } else {

        if ($atTheSameTime >= 1) {
            DB::rollback();
            throw new NotFoundException('reserve.fail.notEnoughTickets');            
        } else {

        $user->flights()->attach($flight_id, array(
            'browser' => $request['browser'],
            'ip_address' => $request['ip_address'],
            'up_load' => $request['up_load'],
            'down_load' => $request['down_load'],
            'connection_method' => $request['connection_method'],
            'status' => 0,
            'jwt' => $jwt
        ));

        $user->reservationLogs()->create([
            'executor_id' => $user->id,
            'flight_id' => $user->id,
            'action' => 1,
        ]);

        DB::commit();

        }}}}}

        $this->dispatch(new SendConfirmReservationEmail($user));

        $plan_id = $flight->plan_id;
        $timestamp = $flight->flight_at->timestamp;

        $response = [
            'reservations' => $user->numberOfReserved(),
            'key' => $plan_id,
            'config' => $this->flights->getConfig(),
            'timetables' => [$this->flights->getTimetable($plan_id, $timestamp)]
        ];

        return \Response::json($response, 200);
    }
}


