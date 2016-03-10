<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\Place\PlaceContract;
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
//Requests
use App\Http\Requests\Api\Backend\Flight\PlaceRequest;

class PlaceController extends Controller
{
    /**
     * @param PlaceContract
     */
    protected $places;

    /**
     * @param PlaceContract $places
     */
    public function __construct(PlaceContract $places)
    {
        $this->places = $places;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function places(PlaceRequest $request)
    {
        $places = $this->places->all()->toArray();
        return \Response::json(['places' => $places], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PlaceRequest $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $input = $request->except('q', 'file');
            $place = $this->places->store($input, $file);
        }
        else {
            $input = $request->except('q');
            $place = $this->places->store($input);            
        }

        $places = $this->places->all()->toArray();
        return \Response::json(['places' => $places], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, PlaceRequest $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $input = $request->except('file');
            $place = $this->places->update($id, $input, $file);
        }
        else {
            $input = $request->except('q');
            $place = $this->places->update($id, $input);            
        }

        $places = $this->places->all()->toArray();
        return \Response::json(['places' => $places], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, PlaceRequest $request)
    {
        $place = $this->places->delete($id);
        $places = $this->places->all()->toArray();
        return \Response::json(['places' => $places], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function picture($id)
    {
        $picture = $users = DB::table('imgs')->where('id', $id)->first();
        //var_dump($picture->data);
        //$picture = $this->places->getPicture($id);
        return \Response::make($picture->data, 200, ['Content-Type' => 'image/*']);
    }    
}
