<?php

namespace App\Repositories\Backend\Flight;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
//Models
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
//Exceptions
use App\Exceptions\NotFoundException;

/**
 * Class EloquentFlightRepository
 * @package App\Repositories\Flight
 */
class EloquentFlightRepository implements FlightContract
{
    /**
     * @return integer
     */
    public function getConfig() :array
    {
        $a = config('flight.start_at'); //開始時刻
        $b = config('flight.end_at');   //終了時刻
        $c = config('flight.time');     //1フライトの時間

        return [$a, $b, $c];
    }

    /**
     * @return array
     */
    public function getTimetable(int $plan_id, int $timestamp, int $i = 0) :array
    {
    	$date = $this->getDateObject($timestamp, $i);

        $a = config('flight.start_at'); //開始時刻
        $b = config('flight.end_at');   //終了時刻
        $c = config('flight.time');     //1フライトの時間

        $flight_at = $date->copy()->addhour($a);
        $n = ( $b - $a ) * 60 / $c;
        
        $flights = [];

        for ($k = 1; $k <= $n; $k++) {
            $flights[] = [
                'plan_id' => $plan_id,
                'period' => $k,
                'flight_at' => $flight_at->toDateTimeString(),
                'numberOfDrones' => '0',
                'deleted_at' => $flight_at->toDateTimeString()
            ];
            $flight_at->addMinute($c);
        }

        $created = Flight::withTrashed()
            ->where('flight_at', '>=', $date)
            ->where('flight_at', '<', $date->copy()->addDay())
            ->where('plan_id', '=', $plan_id)
            ->lockForUpdate()
            ->count();

        if ($created === 0) {
            DB::table('flights')->insert($flights);
        }

        $flights = Flight::with(['users' => function ($query) {
                $query->select('users.id', 'users.name');
            }])
            ->withTrashed()
            ->where('flight_at', '>=', $date)
            ->where('flight_at', '<', $date->copy()->addDay())
            ->where('plan_id', '=', $plan_id)
            ->get(['id', 'flight_at', 'period', 'numberOfDrones', 'deleted_at']);

    	return [
    		$date->timestamp,
    		$flights
    	];

    }

    /**
     * @return Carbon
     */
    public function getDateObject(int $timestamp, int $i = 0, $period = false) :Carbon
    {
        $from_timestamp = Carbon::createFromTimestamp($timestamp);
        $year = $from_timestamp->year;
        $month = $from_timestamp->month;
        $day = $from_timestamp->day;

// $dt->toDateString();// 1975-12-25
// Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')->toDateTimeString(); // 1975-05-21 22:00:00


        $date= Carbon::create($year, $month, $day, 0, 0, 0)->addDay($i);

        if ($period) {
            $date->addHours(config('flight.start_at'))
                ->addMinute(($period - 1) * config('flight.time'));
        }

        return $date;
    }

    /**
     * @return Flight
     */
    public function update(int $flight_id, int $capacity) :Flight
    {
        DB::beginTransaction();

        $flight = Flight::with('users')->find($flight_id);

        if (is_null($flight)) {
            DB::rollback();
            throw new NotFoundException('flight.notFound');
        } else {
        
        if ($flight->flight_at->isPast()) {
            DB::rollback();
            throw new NotFoundException('flight.isPast');          
        } else {

        if ($flight->users()->lockForUpdate()->count() > $capacity) {
            DB::rollback();
            throw new NotFoundException('flight.hasReservations');    
        } else {

            $flight->numberOfDrones = $capacity;
            $flight->save();

            DB::commit();
        }}}

        return $flight;
    }


    /**
     * @return Flight
     */
    public function restore(int $flight_id, int $capacity) :Flight
    {
        DB::beginTransaction();

        $flight = Flight::with('users')->withTrashed()->find($flight_id);

        if (is_null($flight)) {
            DB::rollback();
            throw new NotFoundException('flight.notFound');
        } else {

        if (!$flight->trashed()) {
            DB::rollback();
            throw new NotFoundException('flight.alreadyTrashed');
        } else {

            $flight->restore();
            $flight->numberOfDrones = $capacity;
            $flight->save();

            DB::commit();
        }}

        return $flight;
    }

    /**
     * @return Flight
     */
    public function delete(int $flight_id) :Flight
    {
        DB::beginTransaction();

        $flight = Flight::with('users')->find($flight_id);

        if (is_null($flight)) {
            DB::rollback();
            throw new NotFoundException('flight.notFound');
        } else {
        
        if ($flight->flight_at->isPast()) {
            DB::rollback();
            throw new NotFoundException('flight.isPast');          
        } else {

        if ($flight->users()->lockForUpdate()->count() > 0) {
            DB::rollback();
            throw new NotFoundException('flight.hasReservations');    
        } else {

        $flight->numberOfDrones = 0;
        $flight->save();
        $flight->delete();

        DB::commit();

        }}}

        return $flight;
    }


    /**
     * @return bool
     */
    public function create(int $plan_id, int $timestamp, int $period) :bool
    {
        $flight_at = $this->getDateObject($timestamp, 0, $period);

        if ($flight_at->isPast()) {
           return false;
        }

        DB::beginTransaction();
        if ($this->countForUpdate($plan_id, $flight_at, $period))
        {
            $flight = new Flight;
            $flight->plan_id = $plan_id;
            $flight->flight_at = $flight_at;
            $flight->period = $period;
            $flight->numberOfDrones = 1;
            $flight->save();

            DB::commit();
            return true;
        }
        else {
            DB::rollback();
            return false;
        }
    }

    /**
     * return false if same flight exist already
     * @return bool
     */
    public function countForUpdate(int $plan_id, Carbon $flight_at, int $period) :bool
    {
        $flight = Flight::where('flight_at', '=', $flight_at)
            ->where('plan_id', '=', $plan_id)
            ->where('period', '=', $period)
            ->lockForUpdate()
            ->get();

        if (is_null($flight)) {
            return false;
        }

        return true;
    }
}
