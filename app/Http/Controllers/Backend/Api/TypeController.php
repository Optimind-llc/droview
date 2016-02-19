<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
use App\Http\Requests\Api\Backend\Flight\PlanRequest;

class TypeController extends Controller
{

    public function index()
    {
        return view('backend.flight.index', compact('tickets', 'sum'));
    }

    public function create()
    {
        $type = new Type;
        $type->name = 'サンプル';
        $type->en = 'sample';

        if ($type->save()) {
            return \Response::json($type_id, 200);
        }
        
        throw new NotFoundException('type.create.fail');
    }

    public function getPlacesByType($type_id, $filter)
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
