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
        ->get(['id', 'type_id', 'place_id', 'active', 'description', 'created_at', 'updated_at'])
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
            throw new NotFoundException('plan.notFound');
        }

        return $plan;
    }

    /**
     * @return Plan
     */
    public function create(int $type_id, int $place_id, string $description) :Plan
    {
        DB::beginTransaction();

        $plan = Plan::where('type_id', $type_id)
            ->where('place_id', $place_id)
            ->lockForUpdate()
            ->first();

        if (!is_null($plan)) {
            DB::rollback();
            throw new NotFoundException('plan.alreadyExist');

        } else {
            $plan = new Plan;
            $plan->type_id = $type_id;
            $plan->place_id = $place_id;
            $plan->description = $description;
            $plan->active = 1;
            $plan->save();

            $a = config('flight.start_at'); //開始時刻
            $b = config('flight.end_at');   //終了時刻
            $c = config('flight.time');     //1フライトの時間

            $flight_at = Carbon::today()->addhour($a);
            $n = ( $b - $a ) * 60 / $c;
            
            $flights = [];

            for ($i = 0; $i < 21; $i++) {
                for ($ii = 1; $ii <= $n; $ii++) {
                    $flights[] = [
                        'plan_id' => $plan->id,
                        'period' => $ii,
                        'flight_at' => $flight_at->toDateTimeString(),
                        'numberOfDrones' => '0',
                        'deleted_at' => $flight_at->toDateTimeString()
                    ];
                    $flight_at->addMinute($c);
                }
                $sub = 24 - ( $b - $a );
                $flight_at->addHour($sub);
            }
        
            DB::table('flights')->insert($flights);
            DB::commit();
        }

        return $plan;
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

        throw new NotFoundException('plan.update.faile');
    }

    /**
     * @return bool
     */
    public function delete(int $id) :bool
    {
        DB::beginTransaction();

        $plan = Plan::find($id);

        if ($plan->flights()->lockForUpdate()->count() > 0) {
            DB::rollback();
            throw new NotFoundException('plan.hasFlights');

        } else {
            $plan->delete();
            DB::commit();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function changeStatus(int $id, int $status) :bool
    {
        DB::beginTransaction();

        $plan = Plan::find($id);

        if ($status === 0 && $plan->flights()->lockForUpdate()->count() > 0) {
            DB::rollback();
            throw new NotFoundException('plan.hasFlights');

            return false;

        } else {
            $plan->active = $status;
            $plan->save();
            DB::commit();
        }

        return true;
    }
}
