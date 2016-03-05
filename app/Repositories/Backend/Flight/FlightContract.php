<?php

namespace App\Repositories\Backend\Flight;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Flight\Flight;

/**
 * Interface FlightContract
 * @package App\Repositories\Flight
 */
interface FlightContract
{
    /**
     * @return integer
     */
    public function getConfig() :array;

    /**
     * @return array
     */
    public function getTimetable(int $plan_id, int $timestamp, int $i = 0) :array;

    /**
     * @return Carbon
     */
    public function getDateObject(int $timestamp, int $i = 0, $period = false) :Carbon;

    /**
     * @return Flight
     */
    public function restore(int $flight_id, int $capacity) :Flight;

    /**
     * @return Flight
     */
    public function delete(int $flight_id) :Flight;

    /**
     * @return bool
     */
    public function create(int $plan_id, int $timestamp, int $period) :bool;

    /**
     * return false if same flight exist already
     * @return bool
     */
    public function countForUpdate(int $plan_id, Carbon $flight_at, int $period) :bool;

}
