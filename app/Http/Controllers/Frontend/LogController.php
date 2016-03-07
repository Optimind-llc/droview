<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Frontend\User\UserContract;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
//Request
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Http\Requests\Frontend\User\ChangePasswordRequest;
//Exceptions
use App\Exceptions\NotFoundException;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class LogController extends Controller {

	public function ticket()
	{
        $user = \Auth::user();
        $tickets = $user->tickets()->get(['amount','created_at','method'])->toArray();
        return \Response::json(['ticketLogs' => $tickets]);
	}

	public function reservation()
	{
        $user = \Auth::user();
        $reservations = $user->reservationLogs()->get()->toArray();
        return \Response::json(['reservationLogs' => $reservations]);
	}

}