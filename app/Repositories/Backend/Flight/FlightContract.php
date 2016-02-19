<?php

namespace App\Repositories\Backend\Flight;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface FlightContract
 * @package App\Repositories\Flight
 */
interface FlightContract
{
    /**
     * return false if same flight exist alresdy
     * @param  integer $plan_id
     * @param  Carbon  $flight_at
     * @param  integer $period
     * @return bool
     */
    public function countForUpdate(int $plan_id, Carbon $flight_at, int $period) :bool;

    /**
     * @return integer
     */
    public function getPeriods() :int;

    /**
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $i
     * @return array
     */
    public function getTimetable(int $plan_id, int $timestamp, int $i = 0) :array;

    /**
     * @param  integer $id
     * @return bool
     */
    public function destroy(int $id) :bool;

    /**
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $period
     * @return bool
     */
    public function create(int $plan_id, int $timestamp, int $period) :bool;

    /**
     * @param  integer $plan_id
     * @param  integer $timestamp
     * @param  integer $i
     * @return bool
     */
    public function getFlight(int $plan_id, int $timestamp, int $i) :Collection;

    /**
     * @param  integer $timestamp
     * @param  integer $i
     * @param  integer $period
     * @return Carbon
     */
    public function getDateObject(int $timestamp, $i = 0, $period = false) :Carbon;
}
