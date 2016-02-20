<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\Type\TypeContract;
//Models
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
//Exceptions
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
//Requests
use App\Http\Requests\Api\Backend\Flight\TimetableRequest;
use App\Http\Requests\Api\Backend\Flight\TypeRequest;

class TypeController extends Controller
{
    /**
     * @param TypeContract
     */
    protected $types;

    /**
     * @param TypeContract $types
     */
    public function __construct(TypeContract $types)
    {
        $this->types = $types;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(TypeRequest $request)
    {
        $types = $this->types->all()->toArray();
        return \Response::json(['types' => $types], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TypeRequest $request)
    {
        $type = $this->types->create($request->except('q'));
        $types = $this->types->all()->toArray();
        return \Response::json(['types' => $types], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, TypeRequest $request)
    {
        $type = $this->types->update($id, $request->except('q'));       
        $types = $this->types->all()->toArray();
        return \Response::json(['types' => $types], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, TypeRequest $request)
    {
        $type = $this->types->delete($id);
        $types = $this->types->all()->toArray();
        return \Response::json(['types' => $types], 200);
    }

    public function places($type_id, $filter)
    {
        $opened = Type::find($type_id)
            ->places()
            ->select('places.id')
            ->get()
            ->toArray();

        $opened_ids = [];
        foreach ($opened as $value) {
            array_push($opened_ids, $value['id']);
        }

        $closed = Place::whereNotIn('id', $opened_ids)
            ->select('places.id', 'name', 'path')
            ->get()
            ->toArray();

        return \Response::json(['closedPlaces' => $closed], 200);
    }
}
