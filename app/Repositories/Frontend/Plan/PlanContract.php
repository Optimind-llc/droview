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
    public function getAll() :array;

    /**
     * @return Plan
     */
    public function findById(int $id) :Plan;

    /**
     * @return bool
     */
    public function create(int $type_id, int $place_id, string $description) :Plan;

    /**
     * @return bool
     */
    public function update(int $id, array $input) :bool;

    /**
     * @return bool
     */
    public function delete(int $id) :bool;

    /**
     * @return bool
     */
    public function changeStatus(int $id, int $status) :bool;
}
