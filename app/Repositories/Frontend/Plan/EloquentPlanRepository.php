<?php

namespace App\Repositories\Frontend\Plan;

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
 * Class EloquentPlanRepository
 * @package App\Repositories\Flight
 */
class EloquentPlanRepository implements PlanContract
{
    /**
     * @return Plan
     */
    public function findOrThrowException(int $id) :Plan
    {
    	$user = Plan::find($id);

        if (!is_null($user)) {
            return $user;
        }

        throw new NotFoundException('plan.notFound');
    }

    /**
     * @return bool
     */
    public function countForUpdate(int $type_id, int $place_id) :bool
    {
    	$plans = Plan::where('type_id', $type_id)
    		->where('place_id', $place_id)
    		->lockForUpdate()
    		->count();

        if (!$plans === 0) {
        	return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function all() :array
    {
        $plans = Plan::with([
            'type' => function ($query) {
                $query->select(['id', 'name', 'en']);
            },
            'place' => function ($query) {
                $query->select(['id', 'name']);
            }
        ])
        ->where('active', 1)
        ->get(['id', 'type_id', 'place_id', 'description'])
        ->toArray();

        return $plans;
    }

    /**
     * @return Plan
     */
    public function findById(int $id, bool $with = false) :Plan
    {
        $plan = Plan::with([
            'type' => function ($query) {
                $query->select(['id', 'name', 'en']);
            },
            'place' => function ($query) {
                $query->select(['id', 'name']);
            },
            'flights' => function ($query) {
                $query->with([
                    'users' => function ($query) {
                        $query->select(['users.id']);
                    }
                ])
                ->select(
                    'flights.id',
                    'flights.plan_id',
                    'flights.numberOfDrones',
                    'flights.flight_at'
                );
            }
        ])
        ->find($id);

        if (is_null($plan)) {
            throw new NotFoundException('users.notFound');
        }

        return $plan;
    }
}

