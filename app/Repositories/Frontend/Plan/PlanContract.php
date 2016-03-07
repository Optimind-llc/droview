<?php

namespace App\Repositories\Frontend\Plan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Flight\Plan;

/**
 * Interface PlanContract
 * @package App\Repositories\Flight
 */
interface PlanContract
{
    /**
     * @return User
     */
    public function findOrThrowException(int $id) :Plan;

    /**
     * @return bool
     */
    public function countForUpdate(int $type_id, int $place_id) :bool;

    /**
     * @return array
     */
    public function all() :array;

    /**
     * @return Plan
     */
    public function findById(int $id) :Plan;
}
