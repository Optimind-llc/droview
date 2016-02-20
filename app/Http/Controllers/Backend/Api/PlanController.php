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
    /**
     * @param PlanContract
     */
    protected $plans;

    /**
     * @param PlanContract $plans
     */
    public function __construct(PlanContract $plans)
    {
        $this->plans = $plans;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function plans(PlanRequest $request)
    {
        $plansCollection = $this->plans->getAll();
        $plans = $plansCollection->toArray();

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

        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function plan($id, PlanRequest $request)
    {
        $plan = $this->plans->findById($id);
        return \Response::json(['plans' => [$plan]], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PlanRequest $request)
    {
        $plan = $this->plans->create(
            $request->type,
            $request->place,
            $request->description
        );

        $plan = $this->plans->findById($plan->id);
        return \Response::json(['plans' => [$plan]], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, PlanRequest $request)
    {
        $plan = $this->plans->update($id, $request->except('q'));        
        return \Response::json('ok', 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 0);
        return \Response::json('ok', 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 1);
        return \Response::json('ok', 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, PlanRequest $request)
    {
        $plan = $this->plans->delete($id);
        return \Response::json('ok', 200);
    }
}
