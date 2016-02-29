<?php

namespace App\Repositories\Frontend\Flight;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Access\User\User;
use App\Models\Flight\Flight;

/**
 * Interface FlightContract
 * @package App\Repositories\Flight
 */
interface FlightContract
{
    /**
     * @return Flight
     */
    public function findOrThrowException(int $id): Flight;

    /**
     * @return integer
     */
    public function getConfig() :array;

    /**
     * @return array
     */
    public function getTimetable(int $plan_id, int $timestamp, int $i = 0) :array;

    /**
     * @return bool
     */
    public function getFlight(int $plan_id, int $timestamp, int $i) :Collection;

    /**
     * @return Carbon
     */
    public function getDateObject(int $timestamp, int $i = 0, $period = false) :Carbon;

    /**
     * @return Flight
     */
    public function confirmReservation(int $id, User $user) :Flight;

    /**
     * @return bool
     */
    public function reserve(User $user, int $flight_id, String $jwt) :bool;

}
