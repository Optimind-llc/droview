<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\Plan\PlanContract;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Backend\Flight\TimetableRequest;
use App\Http\Requests\Api\Backend\Flight\PlanRequest;

class PlanController extends Controller
{
    protected $plans;
    /**
     * @param  $id
     * @param  EditUserRequest $request
     * @return mixed
     */
    public function __construct(
        PlanContract $plans
    )
    {
        $this->plans = $plans;
    }

    /**
     * @return mixed
     */
    public function plans(PlanRequest $request)
    {
        $plans = $this->plans->getAll()->toArray();

        foreach ($plans as &$plan)
        {
            unset($plan['type_id'], $plan['place_id']);
            foreach ($plan['flights'] as &$flight)
            {
                unset($flight['plan_id'],
                    $flight['flight_at'],
                    $flight['numberOfDrones'],
                    $flight['period'],
                    $flight['updated_at'],
                    $flight['created_at'],
                    $flight['created_at']);
                $flight['users'] = count($flight['users']);
            }
        }

        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return mixed
     */
    public function plan($id, PlanRequest $request)
    {
        $plan = $this->plans->findById($id);
        return \Response::json(['plan' => $plan], 200);
    }

    /**
     * @return mixed
     */
    public function create(PlanRequest $request)
    {
        $plan = $this->plans->create(
            $request->type,
            $request->place,
            $request->description
        );

        return \Response::json('ok', 200);
    }

    /**
     * @return mixed
     */
    public function update($id, PlanRequest $request)
    {
        $plan = $this->plans->update($id, $request->except('q'));        
        return \Response::json('ok', 200);
    }

    /**
     * @return mixed
     */
    public function deactivate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 0);
        return \Response::json('ok', 200);
    }

    /**
     * @return mixed
     */
    public function activate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 1);
        return \Response::json('ok', 200);
    }

    /**
     * @return mixed
     */
    public function delete($id, PlanRequest $request)
    {
        return \Response::json('ok', 200);
    }
}
