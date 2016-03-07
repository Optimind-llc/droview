<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Frontend\Plan\PlanContract;
//Exceptions
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Frontend\Flight\PlanRequest;

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
        $plans = $this->plans->all();
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
}
