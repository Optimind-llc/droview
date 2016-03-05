<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//Exceptions
use App\Exceptions\NotFoundException;
//Models
use App\Models\Access\User\User;
use App\Models\Flight\Flight;

/**
 * Class DBtestController
 * @package App\Http\Controllers
 */
class DBtestController extends Controller
{
    public function dbtest(int $user_id, int $flight_id)
    {
        DB::beginTransaction();

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
        } else {

        $user = User::find($user_id);
        //完了していないフライト
        $unfinished = $user->flights()->where('status', '0')->lockForUpdate()->count();
        //同時刻に予約しているフライト
        $atTheSameTime = $user->flights()->where('flight_at', $flight_at)->count();
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
            'status' => 0,
            'jwt' => 'DBtest'
        ));

        DB::commit();

        }}}}}

        return 'ok';
    }

    public function dbcheck()
    {
        $users = DB::table('flight_user')
            ->select(DB::raw('user_id, count(*) as reservations'))
            ->groupBy('user_id')
            ->orderBy('reservations', 'desc')
            ->get();

        $flights = DB::table('flight_user')
            ->select(DB::raw('flight_id, count(*) as reservations'))
            ->groupBy('flight_id')
            ->orderBy('reservations', 'desc')
            ->get();

        return \Response::json([
            'user' => $users,
            'flight' => $flights
        ], 200);
    }
}




