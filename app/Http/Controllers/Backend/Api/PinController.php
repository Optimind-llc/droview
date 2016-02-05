<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;

class PinController extends Controller
{

    public function index()
    {
        $pins = Pin::where('status', '=', '0')->get(['pin', 'numberOfTickets', 'created_at']);
        return \Response::json($pins, 200);
    }

    public function generate(Request $inputs)
    {
        $pins = $this->generatePin($inputs['tickets'], $inputs['pins']);
        return \Response::json('ok', 200);
    }

    protected function generatePin($tickets, $pins)
    {
        $result = array();

        $n = 1;
        while ($n <= $pins)
        {
            $pin = new Pin;
            $pin->pin = str_random(16);
            $pin->numberOfTickets = $tickets;
            $pin->status = '0';
            $pin->save();

            $result["$n"]["pinCode"] = $pin['pin'];
            $result["$n"]["numberOfTickets"] = $pin['numberOfTickets'];
            $n += 1;
        }

        return $result;
    }
}
