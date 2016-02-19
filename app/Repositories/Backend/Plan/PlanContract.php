<?php

namespace App\Repositories\Backend\Plan;

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
     * @return Collection
     */
    public function getAll() :Collection;

    /**
     * @return Plan
     */
    public function findById(int $id) :Plan;

    /**
     * @return bool
     */
    public function create(int $type_id, int $place_id, string $description) :bool;

    /**
     * @return Collection
     */
    public function update(int $id, array $input) :bool;
}
