<?php

namespace App\Http\Controllers\Backend\Flight;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;

class FlightController extends Controller
{

    public function index()
    {
        return view('backend.flight.index', compact('tickets', 'sum'));
    }
}
