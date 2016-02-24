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
        $plans = $this->plans->getAll();
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
        $plans = $this->plans->getAll();
        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, PlanRequest $request)
    {
        $plan = $this->plans->update($id, $request->except('q'));        
        $plans = $this->plans->getAll();
        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 0);
        $plans = $this->plans->getAll();
        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate($id, PlanRequest $request)
    {
        $plan = $this->plans->changeStatus($id, 1);
        $plans = $this->plans->getAll();
        return \Response::json(['plans' => $plans], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, PlanRequest $request)
    {
        $plan = $this->plans->delete($id);
        $plans = $this->plans->getAll();
        return \Response::json(['plans' => $plans], 200);
    }
}
