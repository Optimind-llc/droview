<?php

namespace App\Repositories\Backend\Plan;

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
    public function getAll() :array
    {
        $plans = Plan::with([
            'type' => function ($query) {
                $query->select(['id', 'name', 'en']);
            },
            'place' => function ($query) {
                $query->select(['id', 'name', 'path']);
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
        ->get(['id', 'type_id', 'place_id', 'active', 'description'])
        ->toArray();

        foreach ($plans as &$plan)
        {
            foreach ($plan['flights'] as &$flight)
            {
                unset($flight['plan_id'],
                    $flight['period'],
                    $flight['updated_at'],
                    $flight['created_at'],
                    $flight['created_at']);
                $flight['users'] = count($flight['users']);
            }
            unset($plan['type_id'], $plan['place_id']);
        }

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
                $query->select(['id', 'name', 'path']);
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

    /**
     * @return Plan
     */
    public function create(int $type_id, int $place_id, string $description) :Plan
    {
        DB::beginTransaction();
        if ($this->countForUpdate($type_id, $place_id))
        {
	        $plan = new Plan;
    	    $plan->type_id = $type_id;
        	$plan->place_id = $place_id;
            $plan->description = $description;
            $plan->active = 1;

        	if ($plan->save()) {
	            DB::commit();
	            return $plan;
           	}

           	DB::rollback();
           	throw new NotFoundException('users.notFound');
        }
        else
        {
            DB::rollback();
            throw new NotFoundException('users.notFound');
        }
    }

    /**
     * @return Collection
     */
    public function update(int $id, array $input) :bool
    {
        $plan = $this->findOrThrowException($id);
        $plan->description = $input['description'];

        if ($plan->save()) {
            return true;
        }

        throw new NotFoundException('plans.update.faile');
    }

    /**
     * @return bool
     */
    public function delete(int $id) :bool
    {
        $plan = $this->findOrThrowException($id);

        if ($plan->delete()) {
            return true;
        }

        throw new NotFoundException('plans.delete.faile');
    }

    /**
     * @return bool
     */
    public function changeStatus(int $id, int $status) :bool
    {
    	$plan = $this->findOrThrowException($id);
    	$plan->active = $status;

    	if ($plan->save()) {
    		return true;
    	}

        throw new NotFoundException('plans.changeStatus.faile');
    }
}
