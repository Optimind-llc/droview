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
     * return false if same flight exist alresdy
     * @return bool
     */
    public function countForUpdate(int $plan_id, Carbon $flight_at, int $period) :bool
    {
    	//DB::table('users')->where('votes', '>', 100)->lockForUpdate()->get();
        $flight = Flight::where('flight_at', '=', $flight_at)
            ->where('plan_id', '=', $plan_id)
            ->where('period', '=', $period)
            ->get();

        if (is_null($flight)) {
        	return false;
        }

        return true;
    }

    /**
     * @return integer
     */
    public function getPeriods() :int
    {
        $a = config('flight.start_at'); //開始時刻
        $b = config('flight.end_at');   //終了時刻
        $c = config('flight.time');     //1フライトの時間
        $n = ( $b - $a ) * 60 / $c;

        return $n;
    }

    /**
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $i
     * @return array
     */
    public function getTimetable(int $plan_id, int $timestamp, int $i = 0) :array
    {
    	$date = $this->getDateObject($timestamp, $i);

    	return [
    		$date->timestamp,
    		$this->getFlight($plan_id, $timestamp, $i)
    	];
    }

    /**
     * @param  integer $id
     * @return bool
     */
    public function destroy(int $id) :bool
    {
        if (Flight::destroy($id)) {
            return true;
        }

        return false;
    }

    /**
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $period
     * @return bool
     */
    public function create(int $plan_id, int $timestamp, int $period) :bool
    {
    	$flight_at = $this->getDateObject($timestamp, 0, $period);

        DB::beginTransaction();
        if ($this->countForUpdate($plan_id, $flight_at, $period))
        {
            $flight = new Flight;
            $flight->plan_id = $plan_id;
            $flight->flight_at = $flight_at;
            $flight->period = $period;
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
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $i
     * @return bool
     */
    public function getFlight(int $plan_id, int $timestamp, int $i) :Collection
    {
        if (!$timestamp) $date = $this->getDateObject();
        else $date = $this->getDateObject($timestamp, $i);

        $flights = Flight::with(['users' => function ($query) {
			$query->select('users.id');
		}])
        ->where('flight_at', '>=', $date)
        ->where('flight_at', '<', $date->copy()->addDay())
        ->where('plan_id', '=', $plan_id)
        ->get(['id', 'flight_at', 'period', 'numberOfDrones']);

        return $flights;
    }

    /**
     * @param  integer $timestamp
     * @param  integer $i
     * @param  integer $period
     * @return Carbon
     */
    public function getDateObject(int $timestamp, $i = 0, $period = false) :Carbon
    {
        $date = Carbon::createFromTimestamp($timestamp)->addDay($i);

        if ($period) {
            $date->addHours(config('flight.start_at'))
                ->addMinute(($period - 1) * config('flight.time'));
        }

        return $date;
    }
}
