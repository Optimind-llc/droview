<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon as Carbon;
use App\Flight;
use App\Ticket;
use App\FlightUser;

class ConsumptionTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumptionTicket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $flights = Flight::where('flight_at', '<', Carbon::now())->get(['id', 'plan_id'])->toArray();
        foreach ($flights as $flight) {
            $flightUsers = FlightUser::where('flight_id', '=', $flight["id"])
                ->where('status', '=', '0')
                ->get();
            foreach ($flightUsers as $flightUser) {
                $flightUser->status = '1';
                $flightUser->save();

                $ticket = new Ticket;
                $ticket->user_id = $flightUser->user_id;
                $ticket->amount = '-1';
                $ticket->method = $flight['plan_id'];
                $ticket->key = $flightUser->id;
                $ticket->save();
            }
        }
    }
}
