<?php namespace App\Http\Controllers\Backend\Ticket;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Chat\StoreRequest;
use App\Ticket;
use App\User;

class TicketController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function show()
    {
        //$tickets = Ticket::all();
        $user = \Auth::user();

        $tickets = $user->tickets()->get(['action','created_at']);

        $sum = 0;
        foreach ($tickets as $ticket)
        {
            $sum = $sum + $ticket->action;
        }

        return view('frontend.lecture.ticket', compact('tickets', 'sum'));
    }

    public function add()
    {

        $user = \Auth::user();

        $ticket = new Ticket;
        $ticket->user_id = $user->id;
        $ticket->action = '1';
        $ticket->save();

        return redirect(url('/ticket'))->with('message', 'チケットを購入しました');
    }
}
