<?php

namespace App\Repositories\Frontend\Flight;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
//Models
use App\Models\Access\User\User;
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
     * @return Flight
     */
    public function findOrThrowException(int $id): Flight
    {
        $flight = Flight::with('plan.type', 'plan.place')->find($id);

        if (!is_null($flight)) {
            return $flight;
        }

        throw new NotFoundException('flight.notFound');
    }

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

    	return [
    		$date->timestamp,
    		$this->getFlight($plan_id, $timestamp, $i)
    	];
    }

    /**
     * @return bool
     */
    public function getFlight(int $plan_id, int $timestamp, int $i) :Collection
    {
        $date = $this->getDateObject($timestamp, $i);

        $flights = Flight::with(['users' => function ($query) {
			$query->select('users.id');
		}])
        ->where('flight_at', '>=', $date)
        ->where('flight_at', '<', $date->copy()->addDay())
        ->where('plan_id', '=', $plan_id)
        ->get(['id', 'flight_at', 'period', 'numberOfDrones', 'deleted_at']);

        return $flights;
    }

    /**
     * @return Flight
     */
    public function confirmReservation(int $flight_id, User $user) :Flight
    {
        return $flight;
    }

    /**
     * @return bool
     */
    public function reserve(User $user, int $flight_id, String $jwt) :bool
    {
        return true;
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

        $date= Carbon::create($year, $month, $day, 0, 0, 0)->addDay($i);

        if ($period) {
            $date->addHours(config('flight.start_at'))
                ->addMinute(($period - 1) * config('flight.time'));
        }

        return $date;
    }
}
