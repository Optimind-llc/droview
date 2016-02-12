<?php

namespace App\Models\Access\User;

use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;

use Carbon\Carbon;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightUser;
use App\Models\Ticket;

/**
 * Class User
 * @package App\Models\Access\User
 */
class User extends Authenticatable
{

    use SoftDeletes, UserAccess, UserAttribute, UserRelationship;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * flightテーブルとの接続
     *
     * @return object 
     */
    public function flights() {
        return $this
            ->belongsToMany('App\Models\Flight\Flight')
            ->withPivot('id', 'environment_id', 'status', 'jwt');
    }

    /**
     * flight_userテーブルとの接続
     *
     * @return object 
     */
    public function flightUser() {
        return $this->hasMany('App\Models\Flight\FlightUser');
    }

    /**
     * ticketテーブルとの接続
     *
     * @return object 
     */
    public function tickets() {
        return $this->hasMany('App\Models\Ticket');
    }

    /**
     * 現在のチケット残数
     *
     * @return intval 
     */
    public function remainingTickets() {
        $tickets = $this->tickets()->get();

        $remainingTickets = 0;
        foreach ($tickets as $ticket)
        {
            $remainingTickets = $remainingTickets + $ticket->amount;
        }

        return $remainingTickets;
    }

    /**
     * 現在予約している講座数
     *
     * @return intval 
     */
    public function numberOfReserved() {
        $numberOfReserved = $this
            ->flights()
            ->where('flight_at', '>=', Carbon::now())
            ->count();

        return $numberOfReserved;
    }

    /**
     * チケットの消費が行われていないFlightがあればチケットを消費
     *
     * @return intval 
     */
    public function consumptionTicket() {
        $finishedFlights = $this
            ->flights()
            ->where('flight_at', '<', Carbon::now())
            ->get(['plan_id'])
            ->toArray();

        foreach ($finishedFlights as $flight) {
            if ($flight['pivot']['status'] == "0") {
                $id = $flight['pivot']['id'];
                $plan_id = $flight['plan_id'];

                $flightUser = FlightUser::find($id);
                $flightUser->status = '1';
                $flightUser->save();

                $ticket = new Ticket;
                $ticket->user_id = $flightUser->user_id;
                $ticket->amount = '-1';
                $ticket->method = $plan_id;
                $ticket->key = $flightUser->id;
                $ticket->save();
            }
        }
    }

    /**
     * 未完了のフライトのJWTを取得
     *
     * @return intval 
     */
    public function getJwt() {
        $Flights = $this
            ->flights()
            ->where('flight_at', '>=', Carbon::now()->subMinute(config('flight.time')))
            ->get(['plan_id'])
            ->toArray();

        $jwts = array();
        foreach ($Flights as $flight) {
            $jwts = $jwts + array($flight['pivot']['id'] => $flight['pivot']['jwt']);
        }
        return $jwts;
    }

    /**
     * 予約可能上限に達しているか
     *
     * @return bool 
     */
    public function reachTheLimitOfReservations() {
        if ($this->numberOfReserved() >= config('flight.enable_number_of_reservations')) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * チケッットの所持数が十分かどうか
     *
     * @return bool 
     */
    public function notHaveEnoughTickets() {
        if ($this->numberOfReserved() >= $this->remainingTickets()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 同時刻に既に予約をしているかどうか
     *
     * @return bool 
     */
    public function alreadyReservedAtSameTime($flight_id) {
        $flight_at = Flight::findOrFail($flight_id)->flight_at;
        $count = $this
            ->flights()
            ->where('flight_at', '=', $flight_at)
            ->count();

        if ($count) {
            return true;
        }
        else {
            return false;
        }
    }
}
